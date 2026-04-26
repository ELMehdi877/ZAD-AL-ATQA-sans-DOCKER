<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHalaqaRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Competition;
use App\Models\Halaqa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     */
    public function dashboard()
    {

        $usersCount = User::count();
        $activeUsersCount = User::where('statut', 'active')->count();
        $inactiveUsersCount = User::where('statut', 'inactive')->count();
        $parentsCount = User::where('role', 'parent')->count();
        $studentsCount = User::where('role', 'student')->count();
        $cheilhsCount = User::where('role', 'cheikh')->count();
        $halaqasCount = Halaqa::count();
        $competitionsCount = Competition::count();

        return view('admin.dashboard', compact('usersCount', 'activeUsersCount', 'inactiveUsersCount', 'parentsCount', 'studentsCount', 'cheilhsCount', 'halaqasCount', 'competitionsCount'));
    }
    
    /**
     * Affiche la liste de tous les utilisateurs.
     */
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }
        
    /**
     * Affiche le formulaire de création d'un utilisateur.
     */
    public function createUserPage()
    {
        $parents = User::where('role', 'parent')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.create', compact('parents'));
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function storeUser(StoreUserRequest $request)
    {
        $data = $request->validated();
        $parentId = $data['parent_id'] ?? null;
        $nombreHifz = $data['nombre_hifz'] ?? null;

        unset($data['nombre_hifz']);
        unset($data['parent_id']);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($user->role === 'student') {
            $user->student()->create([
                'parent_id' => $parentId,
                'nombre_hifz' => $nombreHifz,
            ]);
        }

        return redirect()->route('users.index')
                ->with('success', 'Nouveau user '.$user->nom.' créé !');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     */
    public function editUserPage(User $user)
    {
        $user->load('student');

        $parents = User::where('role', 'parent')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.edit', compact('user', 'parents'));
    }

    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function updateUser(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $parentId = $data['parent_id'] ?? null;
        $nombreHifz = $data['nombre_hifz'] ?? null;

        unset($data['nombre_hifz']);
        unset($data['parent_id']);

        // Supprime le password si vide pour ne pas écraser l'existant
        if (empty($data['password'])) {
            unset($data['password']);
        }
        else {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);

        if ($user->role === 'student') {
            $user->student()->updateOrCreate(
                ['user_id' => $user->id,],
                [
                    'parent_id' => $parentId,
                    'nombre_hifz' => $nombreHifz
                ]
            );
        }

        return redirect()->route('users.index')
                ->with('success', 'update valider user '. $user->nom);
    }

    /**
     * Supprime un utilisateur.
     */
    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'delete user '.$user->nom);
    }

    /**
     * Active ou désactive le compte d'un utilisateur.
     */
    public function statusUser(int $id)
    {
        $user = User::findOrfail($id);

        if ($user->statut === 'active') {
            $user->update([
            'statut' => 'inactive'
            ]);
        }

        else {
            $user->update([
            'statut' => 'active'
            ]);
        }
        

        return redirect()->route('users.index')
            ->with('success', 'statut mis a jour');
    }

    /**
     * Recherche les utilisateurs par nom.
     */
    public function searchUserByName(Request $request)
    {
        $request->validate([
            'nom' => 'required|string'
        ]);
        
        $users = User::where('nom', 'like', '%' . $request->nom . '%')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function filterUsersByRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,cheikh,student,parent'
        ]);

        $users = User::where('role', $request->role)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }
}