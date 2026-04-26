<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CheikhController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\HalaqaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/dashboard', function () {
        $role = Auth::user()?->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'student' => redirect()->route('student.participations'),
            'parent' => redirect()->route('parent.dashboard'),
            'cheikh' => redirect()->route('cheikh.dashboard'),
            default => redirect()->route('login')->with('error', 'Role utilisateur non reconnu.'),
        };
    })->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:student')->group(function () {
        
        /*
         * ====================================
         * Routes pour la gestion des Halaqas
         * ====================================
         */

        // Afficher toutes les Halaqas de l'étudiant
        Route::get('/student/halaqas', [HalaqaController::class, 'index'])->name('student.halaqas');
        
        // Afficher la Halaqa active actuelle
        Route::get('/student/halaqa-actuelle', [HalaqaController::class, 'currentHalaqa'])->name('student.current-halaqa');
        
        // Rechercher dans la Halaqa actuelle par sourate ou date
        Route::get('/student/halaqas/{halaqa}/search', [HalaqaController::class, 'searchCurrentHalaqa'])->name('student.halaqas.search');
        
        /*
         * ================================================
         * Routes pour les compétitions et participations
         * ================================================
         */

        // Afficher l'historique des participations
        Route::get('/student/participations', [ParticipationController::class, 'studentParticipations'])->name('student.participations');
        
        // Afficher les compétitions disponibles
        Route::get('/student/competitions', [CompetitionController::class, 'index'])->name('student.competitions');
        
        // S'inscrire à une compétition
        Route::post('/student/competitions/{competition}/participate', [ParticipationController::class, 'participate'])->name('student.participate');
        
        // Annuler l'inscription à une compétition
        Route::delete('/student/competitions/{competition}/cancel', [ParticipationController::class, 'cancel'])->name('student.cancel');
        
        // Afficher l'évaluation obtenue lors d'une compétition
        Route::get('/student/competitions/{competition}/evaluation', [ParticipationController::class, 'showStudentEvaluation'])->name('student.showParticipation');
        
        /*
         * ==========================================
         * Routes pour l'historique des évaluations
         * ==========================================
         */

        // Afficher tout l'historique des évaluations
        Route::get('/student/evaluations/historique', [EvaluationController::class, 'historiqueForStudent'])->name('student.evaluations.historique');
        
        // Rechercher dans l'historique des évaluations
        Route::get('/student/evaluations/search', [EvaluationController::class, 'searchForStudent'])->name('student.evaluations.search');
    });

    Route::middleware('role:parent')->group(function () {
        
        /*
         * ==================================
         * Routes du tableau de bord parent
         * ==================================
         */

        // Afficher le tableau de bord principal
        Route::get('/parent/dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
        
        // Afficher la liste des enfants
        Route::get('/parent/children', [ParentController::class, 'index'])->name('parent.children');
        
        /*
         * ====================================================
         * Routes pour les Halaqas et évaluations des enfants
         * ====================================================
         */

        // Afficher les Halaqas d'un enfant
        Route::get('/parent/children/{student}/halaqas', [HalaqaController::class, 'showChildHalaqas'])->name('parent.children.halaqas');
        
        // Afficher l'historique complet des évaluations d'un enfant
        Route::get('/parent/children/{student}/evaluations/historique', [EvaluationController::class, 'showChildEvaluationsHistorique'])->name('parent.children.evaluations.historique');
        
        // Rechercher dans l'historique d'un enfant par sourate ou date
        Route::get('/parent/children/{student}/evaluations/historique/search', [EvaluationController::class, 'searchChildEvaluationsHistorique'])->name('parent.children.evaluations.historique.search');
        
        // Afficher les évaluations d'une Halaqa spécifique
        Route::get('/parent/children/{student}/evaluations/{halaqa}', [EvaluationController::class, 'showChildEvaluations'])->name('parent.children.evaluations');
        
        // Rechercher des évaluations dans une Halaqa spécifique
        Route::get('/parent/children/{student}/evaluations/{halaqa}/search', [EvaluationController::class, 'searchChildEvaluations'])->name('parent.children.evaluations.search');
        
        /*
         * ==========================================
         * Routes pour les compétitions des enfants
         * ==========================================
         */

        // Afficher l'historique des compétitions d'un enfant
        Route::get('/parent/children/{student}/competitions', [ParticipationController::class, 'showChildCompetitions'])->name('parent.children.competitions');
        
        // Afficher le détail des participations d'un enfant pour une compétition
        Route::get('/parent/children/{student}/competitions/{competition}', [ParticipationController::class, 'showChildParticipations'])->name('parent.children.participations');
    });

    Route::middleware('role:cheikh')->group(function () {
        
        /*
         * ==========================================
         * Routes du tableau de bord et des Halaqas
         * ==========================================
         */

        // Afficher le tableau de bord Cheikh
        Route::get('/cheikh/dashboard', [CheikhController::class, 'index'])->name('cheikh.dashboard');
        
        // Afficher toutes les Halaqas
        Route::get('/cheikh/halaqas', [HalaqaController::class, 'index'])->name('cheikh.halaqas');
        
        // Afficher le détail d'une Halaqa pour faire l'appel et les évaluations
        Route::get('/cheikh/halaqas/{halaqa}', [CheikhController::class, 'showHalaqa'])->name('cheikh.halaqas.show');
        
        // Afficher l'historique des évaluations d'une Halaqa
        Route::get('/cheikh/halaqas/{halaqa}/historique', [EvaluationController::class, 'historiqueHalaqa'])->name('cheikh.halaqas.historique');
        
        // Rechercher dans l'historique d'une Halaqa
        Route::get('/cheikh/halaqas/{halaqa}/historique/search', [EvaluationController::class, 'search'])->name('cheikh.halaqas.search');
        
        // Afficher la Halaqa actuelle du Cheikh
        Route::get('/cheikh/halaqa-actuelle', [HalaqaController::class, 'index'])->name('cheikh.current-halaqa');

        /*
         * ==============================================================
         * Routes pour la gestion des étudiants et de leurs évaluations
         * ==============================================================
         */

        // Afficher l'historique d'un étudiant en particulier
        Route::get('/cheikh/halaqas/{halaqa}/students/{student}/historique', [EvaluationController::class, 'historiqueStudent'])->name('cheikh.students.historique');
        
        // Mettre à jour le statut "Hifz" d'un étudiant
        Route::patch('/cheikh/halaqas/{halaqa}/students/{student}/hifz', [CheikhController::class, 'updateEtudiantHifz'])->name('cheikh.students.hifz.update');
        
        // Enregistrer une nouvelle évaluation
        Route::post('/cheikh/evaluations', [EvaluationController::class, 'store'])->name('cheikh.evaluations.store');
        
        // Modifier une évaluation
        Route::put('/cheikh/evaluations/{evaluation}', [EvaluationController::class, 'update'])->name('cheikh.evaluations.update');
        
        // Supprimer une évaluation
        Route::delete('/cheikh/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('cheikh.evaluations.delete');
        
        /*
         * =========================================
         * Routes pour la gestion des compétitions
         * =========================================
         */

        // Afficher les compétitions
        Route::get('/cheikh/competitions', [CompetitionController::class, 'index'])->name('cheikh.competitions');
        
        // Afficher les participations globales aux compétitions
        Route::get('/cheikh/participations', [ParticipationController::class, 'index'])->name('cheikh.participations');
        
        // Évaluer la participation d'un étudiant à une compétition
        Route::patch('/cheikh/competitions/{competition}/students/{student}/evaluation', [ParticipationController::class, 'evaluateStudent'])->name('cheikh.competitions.students.evaluation');
        
        // Supprimer l'évaluation d'une compétition
        Route::delete('/cheikh/competitions/{competition}/students/{student}/evaluation', [ParticipationController::class, 'deleteEvaluation'])->name('cheikh.competitions.students.evaluation.delete');
        
        /*
         * =====================================================
         * Routes pour la gestion des Réunions Visio (Meetings)
         * =====================================================
         */

        // Créer une nouvelle salle de réunion
        Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        
        // Enregistrer la salle (générer l'URL)
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
        
        // Envoyer le lien de la salle aux étudiants concernés
        Route::post('/meetings/{meeting}/send-link', [MeetingController::class, 'sendLink'])->name('meetings.send_link');
    });

    Route::middleware('role:cheikh,student')->group(function () {
        
        /*
         * =================================================================
         * Routes partagées (Cheikh et Étudiant) pour l'accès aux Réunions
         * =================================================================
         */
        
        // Afficher la liste des réunions (selon le rôle)
        Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
        
        // Préparer ou afficher la salle (redirige vers l'action join)
        Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
        
        // Générer le jeton et rejoindre la salle
        Route::get('/meetings/{meeting}/join', [MeetingController::class, 'join'])->name('meetings.join');
    });

    Route::middleware('role:admin')->group(function () {

        //Dashboard
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        /*        
         * =========================================
         * Routes pour la gestion des utilisateurs
         * =========================================
         */

        //Afficher le formulaire de creation d'un utilisateur
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

        //Enregistrer un utilisateur
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        //Afficher le formulaire de modification d'un utilisateur
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        //Modifie les infos d'un utilisateur
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        //Afficher tous les utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        //Modifier le statut d'un utilisateur
        Route::patch('/users/{id}/statut', [UserController::class, 'status'])->name('users.statut');

        //Supprimer un utilisateur
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        //Rechercher un utilisateur par son nom
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/filter', [UserController::class, 'filter'])->name('users.filter');


        /*  
         * ====================================
         * Routes pour la gestion des Halaqas
         * ====================================
         */

        //Afficher le formulaire de creation d'un Halaqa
        Route::get('/halaqas/create', [HalaqaController::class, 'createHalaqaPage'])->name('halaqas.create');

        //Enregistrer un Halaqa
        Route::post('/halaqas', [HalaqaController::class, 'store'])->name('halaqas.store');

        //Afficher le formulaire de modification d'un Halaqa
        Route::get('/halaqas/{halaqa}/edit', [HalaqaController::class, 'edit'])->name('halaqas.edit');

        //Afficher les details d'un Halaqa
        Route::get('/halaqas/{halaqa}', [HalaqaController::class, 'show'])->name('halaqas.show');

        //Modifier les infos d'un Halaqa
        Route::put('/halaqas/{halaqa}', [HalaqaController::class, 'update'])->name('halaqas.update');

        //Supprimer un Halaqa
        Route::delete('/halaqas/{halaqa}', [HalaqaController::class, 'destroy'])->name('halaqas.destroy');

        //Afficher tous les Halaqas
        Route::get('/halaqas', [HalaqaController::class, 'index'])->name('halaqas.index');

        /*
         * =========================================
         * Routes pour la gestion des Competitions
         * =========================================
         */

        //Afficher le formulaire de creation d'une Competition
        Route::get('/competitions/create', [CompetitionController::class, 'create'])->name('competitions.create');

        //Enregistrer une Competition
        Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');

        //Afficher le formulaire de modification d'une Competition
        Route::get('/competitions/{competition}/edit', [CompetitionController::class, 'edit'])->name('competitions.edit');

        //Modifier les infos d'une Competition
        Route::put('/competitions/{competition}', [CompetitionController::class, 'update'])->name('competitions.update');

        //changer le statut d'une Competition
        Route::patch('/competitions/{competition}', [CompetitionController::class, 'statusCompetition'])->name('competitions.statut');

        //Afficher toutes les Competitions
        Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');

        //Afficher toutes les participations
        Route::get('/participations', [ParticipationController::class, 'index'])->name('participations.index');


    });

    Route::middleware('role:cheikh,admin')->group(function () {
        //Afficher une Competition
        Route::get('/competitions/{competition}', [CompetitionController::class, 'show'])->name('competitions.show');

        //Accepter ou refuser une participation
        Route::patch('/participations/{participation}/statut', [ParticipationController::class, 'acceptParticipation'])->name('participations.statut');
    });
});

require __DIR__ . '/auth.php';
