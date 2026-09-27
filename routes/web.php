<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAssignmentController;
// ==========================================
// 1. PUBLIC ROUTES (Login User Biasa)
// ==========================================

// Halaman Login User Biasa
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Google Login Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Logout Route (Digunakan oleh Admin & User)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// 2. ADMIN ROUTES (Prefix: /admin)
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');

    Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('admin.projects.create');
    Route::post('/projects/store', [AdminProjectController::class, 'store'])->name('admin.projects.store');
    });

    Route::prefix('projects')->name('admin.projects.')->group(function () {
        Route::get('/', [AdminProjectController::class, 'index'])->name('index'); // List Project
        Route::get('/create', [AdminProjectController::class, 'create'])->name('create');
        Route::post('/store', [AdminProjectController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [AdminProjectController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProjectController::class, 'update'])->name('update');
    });

    Route::prefix('assignments')->name('admin.assignments.')->group(function () {
        Route::get('/', [AdminAssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AdminAssignmentController::class, 'create'])->name('create');
        Route::post('/store', [AdminAssignmentController::class, 'store'])->name('store');
        Route::get('/scanner-check-in', [AdminAssignmentController::class, 'scannerCheckIn'])->name('scannerCheckIn');
        Route::get('/check-active-task/{userId}', [AdminAssignmentController::class, 'checkUserActiveTask'])->name('checkTask');
        Route::post('/verify-task', [AdminAssignmentController::class, 'verifyTask'])->name('verify');
    });
});


// ==========================================
// 3. USER / MEMBER ROUTES
// ==========================================
Route::middleware('auth')->group(function () {

    // Redirect root '/' ke dashboard yang sesuai
    Route::get('/', function() {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    });

    // Dashboard Utama User (Penjahit)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fitur Profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Route Update Stok (Workdesk)
    Route::post('/assignment/{id}/update', [DashboardController::class, 'updateProgress'])->name('assignment.update');
});
