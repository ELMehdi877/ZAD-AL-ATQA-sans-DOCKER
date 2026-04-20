<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CheikhController;
use App\Http\Controllers\HalaqaController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
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
        Route::get('/student/halaqas', [HalaqaController::class, 'index'])->name('student.halaqas');
        Route::get('/student/halaqa-actuelle', [StudentController::class, 'currentHalaqa'])->name('student.current-halaqa');
        Route::get('/student/halaqas/{halaqa}/search', [StudentController::class, 'searchBySourateOrdateCurrentHalaqa'])->name('student.halaqas.search');
        Route::get('/student/participations', [StudentController::class, 'participations'])->name('student.participations');
        Route::get('/student/competitions', [CompetitionController::class, 'index'])->name('student.competitions');
        Route::post('/student/competitions/{competition}/participate', [StudentController::class, 'participateCompetition'])->name('student.participate');
        Route::delete('/student/competitions/{competition}/cancel', [StudentController::class, 'cancelParticipation'])->name('student.cancel');
        Route::get('/student/competitions/{competition}/evaluation', [StudentController::class, 'showCompetitionEvaluation'])->name('student.showParticipation');
        Route::get('/student/evaluations/historique', [StudentController::class, 'historiqueEvaluations'])->name('student.evaluations.historique');
        Route::get('/student/evaluations/search', [StudentController::class, 'searchBySourateOrdate'])->name('student.evaluations.search');
    });

    Route::middleware('role:parent')->group(function () {
        Route::get('/parent/dashboard', [ParentController::class, 'index'])->name('parent.dashboard');
        Route::get('/parent/children', [ParentController::class, 'index'])->name('parent.children');
        Route::get('/parent/children/{student}/halaqas', [ParentController::class, 'showchildHalaqas'])->name('parent.children.halaqas');
        Route::get('/parent/children/{student}/evaluations/historique', [ParentController::class, 'showchildEvaluationsHistorique'])->name('parent.children.evaluations.historique');
        Route::get('/parent/children/{student}/evaluations/historique/search', [ParentController::class, 'searchChildEvaluationsHistoriqueBySourateOrDate'])->name('parent.children.evaluations.historique.search');
        Route::get('/parent/children/{student}/evaluations/{halaqa}', [ParentController::class, 'showchildEvaluations'])->name('parent.children.evaluations');
        Route::get('/parent/children/{student}/evaluations/{halaqa}/search', [ParentController::class, 'searchChildEvaluationsBySourateOrDate'])->name('parent.children.evaluations.search');
        Route::get('/parent/children/{student}/competitions', [ParentController::class, 'showchildCompetitions'])->name('parent.children.competitions');
        Route::get('/parent/children/{student}/competitions/{competition}', [ParentController::class, 'showchildParticipations'])->name('parent.children.participations');
    });

    Route::middleware('role:cheikh')->group(function () {
        Route::get('/cheikh/dashboard', [CheikhController::class, 'index'])->name('cheikh.dashboard');
        Route::get('/cheikh/halaqas', [HalaqaController::class, 'index'])->name('cheikh.halaqas');
        Route::get('/cheikh/halaqas/{halaqa}', [CheikhController::class, 'showHalaqa'])->name('cheikh.halaqas.show');
        Route::get('/cheikh/halaqas/{halaqa}/historique', [CheikhController::class, 'historiqueEvaluationsHalaqa'])->name('cheikh.halaqas.historique');
        Route::get('/cheikh/halaqas/{halaqa}/historique/search', [CheikhController::class, 'searchByDateOrNomOrPrenom'])->name('cheikh.halaqas.search');
        Route::get('/cheikh/halaqas/{halaqa}/students/{student}/historique', [CheikhController::class, 'historiqueEvaluationsStudent'])->name('cheikh.students.historique');
        Route::patch('/cheikh/halaqas/{halaqa}/students/{student}/hifz', [CheikhController::class, 'updateEtudiantHifz'])->name('cheikh.students.hifz.update');
        Route::post('/cheikh/evaluations', [CheikhController::class, 'storeEvaluation'])->name('cheikh.evaluations.store');
        Route::put('/cheikh/evaluations/{evaluation}', [CheikhController::class, 'updateEvaluation'])->name('cheikh.evaluations.update');
        Route::delete('/cheikh/evaluations/{evaluation}', [CheikhController::class, 'deleteEvaluation'])->name('cheikh.evaluations.delete');
        Route::get('/cheikh/halaqa-actuelle', [HalaqaController::class, 'index'])->name('cheikh.current-halaqa');
        Route::get('/cheikh/competitions', [CompetitionController::class, 'index'])->name('cheikh.competitions');
        Route::get('/cheikh/participations', [ParticipationController::class, 'index'])->name('cheikh.participations');
        Route::patch('/cheikh/competitions/{competition}/students/{student}/evaluation', [CheikhController::class, 'evaluationStudentCompetition'])->name('cheikh.competitions.students.evaluation');
        Route::delete('/cheikh/competitions/{competition}/students/{student}/evaluation', [CheikhController::class, 'deleteEvaluationStudentCompetition'])->name('cheikh.competitions.students.evaluation.delete');
        Route::post('/meetings/{meeting}/send-link', [MeetingController::class, 'sendLink'])->name('meetings.send_link');
        Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    });

    Route::middleware('role:cheikh,student')->group(function () {
        Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
        Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
        Route::get('/meetings/{meeting}/join', [MeetingController::class, 'join'])->name('meetings.join');
    });

    Route::middleware('role:admin')->group(function () {

        //Dashboard
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        /*        
         * Routes pour la gestion des utilisateurs
         */

        //Afficher le formulaire de creation d'un utilisateur
        Route::get('/users/create', [AdminController::class, 'createUserPage'])->name('users.create');

        //Enregistrer un utilisateur
        Route::post('/user.store', [AdminController::class, 'storeUser'])->name('user.store');

        //Afficher le formulaire de modification d'un utilisateur
        Route::get('/users/{user}/edit', [AdminController::class, 'editUserPage'])->name('users.edit');

        //Modifie les infos d'un utilisateur
        Route::put('/user.update/{user}', [AdminController::class, 'updateUser'])->name('user.update');

        //Afficher tous les utilisateurs
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');

        //Modifier le statut d'un utilisateur
        Route::patch('/user.statut/{id}', [AdminController::class, 'statusUser'])->name('user.statut');

        //Supprimer un utilisateur
        Route::delete('/user.supprime/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');

        //Rechercher un utilisateur par son nom
        Route::get('/user.search', [AdminController::class, 'searchUserByName'])->name('user.search');
        Route::get('/user.filter', [AdminController::class, 'filterUsersByRole'])->name('user.filter');


        /*  
         * Routes pour la gestion des Halaqas
         */

        //Afficher le formulaire de creation d'un Halaqa
        Route::get('/halaqas/create', [HalaqaController::class, 'createHalaqaPage'])->name('halaqas.create');

        //Enregistrer un Halaqa
        Route::post('/halaqa.store', [HalaqaController::class, 'store'])->name('halaqa.store');

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
         * Routes pour la gestion des Competitions
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
