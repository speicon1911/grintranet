<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SocialAuthController;

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ScheduleTemplateController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\AusenciaController;
use App\Http\Controllers\DocumentoInstitucionalController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\CategoriaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Google Auth Routes
Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Group routes
    Route::resource('groups', GroupController::class);

    // Only admins can manage users and roles
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users/import', [\App\Http\Controllers\ImportController::class, 'index'])->name('users.import');
        Route::post('users/import', [\App\Http\Controllers\ImportController::class, 'import'])->name('users.import.process');
        
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('zonas', ZonaController::class);
    });

    // Calendar Routes
    Route::get('/calendar', [HolidayController::class, 'index'])->name('calendar.index');
    
    // Management routes for directiva/admin
    Route::middleware(['role:admin|directiva'])->group(function () {
        Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
        Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
    });

    // Schedule Templates
    Route::resource('schedule-templates', ScheduleTemplateController::class);
    Route::get('schedule-templates/{id}/preview', [ScheduleTemplateController::class, 'preview'])->name('schedule-templates.preview');

    // Personal Schedules
    Route::resource('personal-schedules', \App\Http\Controllers\PersonalScheduleController::class);

    // Ausencias
    Route::resource('ausencias', AusenciaController::class);

    // Documentos Institucionales - Acceso de consulta para todos los autenticados
    Route::get('documentos', [DocumentoInstitucionalController::class, 'index'])->name('documentos.index');
    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');

    // Gestión Documental - Solo Admin y Directiva pueden crear, editar o borrar
    Route::middleware(['role:admin|directiva'])->group(function () {
        Route::resource('documentos', DocumentoInstitucionalController::class)->except(['index', 'show']);
        Route::resource('etiquetas', EtiquetaController::class);
        Route::resource('categorias', CategoriaController::class)->except(['index', 'show']);
        // Route::resource('tipo-recursos', \App\Http\Controllers\TipoRecursoController::class);
    });

    // Esta ruta debe ir después de la definición del resource para no capturar 'documentos/create'
    Route::get('documentos/{documento}', [DocumentoInstitucionalController::class, 'show'])->name('documentos.show');

    // AulaPass Integration
    Route::middleware(['role:admin|profesor|conserje'])->group(function () {

        
        Route::get('/aula', [\App\Http\Controllers\HallPassController::class, 'index'])->name('aula.index');
        Route::post('/aula/pass', [\App\Http\Controllers\HallPassController::class, 'store'])->name('aula.store');
        Route::post('/aula/return-all', [\App\Http\Controllers\HallPassController::class, 'returnAll'])->name('aula.return-all');
        Route::patch('/aula/pass/{hallPass}', [\App\Http\Controllers\HallPassController::class, 'update'])->name('aula.update');
        Route::get('/aula/monitor', [\App\Http\Controllers\HallPassController::class, 'monitor'])->name('aula.monitor');
        Route::get('/aula/history', [\App\Http\Controllers\HallPassController::class, 'history'])->name('aula.history');
    });
});
