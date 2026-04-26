<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * --- ACTEUR : ADMIN ---
     * Affiche la liste globale de tous les utilisateurs inscrits sur la plateforme.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Affiche le formulaire de création d'un nouvel utilisateur (Admin, Cheikh, Étudiant ou Parent).
     */
    public function create()
    {
        $parents = User::where('role', 'parent')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.create', compact('parents'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Enregistre un nouvel utilisateur et crée le profil Étudiant si nécessaire.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $parentId = $data['parent_id'] ?? null;
        $nombreHifz = $data['nombre_hifz'] ?? null;

        unset($data['nombre_hifz']);
        unset($data['parent_id']);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Si l'utilisateur est un étudiant, on crée son entrée dans la table students
        if ($user->role === 'student') {
            $user->student()->create([
                'parent_id' => $parentId,
                'nombre_hifz' => $nombreHifz,
            ]);
        }

        return redirect()->route('users.index')
                ->with('success', 'Utilisateur '.$user->nom.' créé avec succès !');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Affiche le formulaire d'édition pour modifier les informations d'un utilisateur.
     */
    public function edit(User $user)
    {
        $user->load('student');

        $parents = User::where('role', 'parent')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.edit', compact('user', 'parents'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Met à jour les informations d'un utilisateur existant.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $parentId = $data['parent_id'] ?? null;
        $nombreHifz = $data['nombre_hifz'] ?? null;

        unset($data['nombre_hifz']);
        unset($data['parent_id']);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Mise à jour ou création du profil étudiant associé
        if ($user->role === 'student') {
            $user->student()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'parent_id' => $parentId,
                    'nombre_hifz' => $nombreHifz
                ]
            );
        }

        return redirect()->route('users.index')
                ->with('success', 'Mise à jour validée pour l\'utilisateur '. $user->nom);
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Supprime définitivement un utilisateur de la base de données.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur '.$user->nom.' supprimé.');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Active ou désactive le compte d'un utilisateur (basculement de statut).
     */
    public function status(int $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'statut' => ($user->statut === 'active') ? 'inactive' : 'active'
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Statut de ' . $user->nom . ' mis à jour.');
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Recherche un utilisateur par son nom au sein de la liste globale.
     */
    public function search(Request $request)
    {
        $request->validate(['nom' => 'required|string']);

        $users = User::where('nom', 'like', '%' . $request->nom . '%')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * --- ACTEUR : ADMIN ---
     * Filtre les utilisateurs par rôle (Admin, Cheikh, Student, Parent).
     */
    public function filter(Request $request)
    {
        $request->validate(['role' => 'required|in:admin,cheikh,student,parent']);

        $users = User::where('role', $request->role)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }
}
