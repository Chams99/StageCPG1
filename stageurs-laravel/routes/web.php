<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EtudiantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::post('/login', [HomeController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
Route::get('/access-denied', [HomeController::class, 'accessDenied'])->name('access.denied');
Route::get('/error', [HomeController::class, 'error'])->name('error');

// Admin routes (protected)
Route::middleware('auth:admin')->group(function () {
    // Admin dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Faculty management
    Route::get('/admin/faculties', [AdminController::class, 'faculties'])->name('admin.faculties');
    Route::post('/admin/faculties', [AdminController::class, 'storeFaculty'])->name('admin.faculties.store');
    Route::put('/admin/faculties/{faculty}', [AdminController::class, 'updateFaculty'])->name('admin.faculties.update');
    Route::delete('/admin/faculties/{faculty}', [AdminController::class, 'deleteFaculty'])->name('admin.faculties.delete');
    
    // Section management
    Route::get('/admin/sections', [AdminController::class, 'sections'])->name('admin.sections');
    Route::post('/admin/sections', [AdminController::class, 'storeSection'])->name('admin.sections.store');
    Route::put('/admin/sections/{section}', [AdminController::class, 'updateSection'])->name('admin.sections.update');
    Route::delete('/admin/sections/{section}', [AdminController::class, 'deleteSection'])->name('admin.sections.delete');
    
    // Supervisor management
    Route::get('/admin/encadreurs', [AdminController::class, 'encadreurs'])->name('admin.encadreurs');
    Route::post('/admin/encadreurs', [AdminController::class, 'storeEncadreur'])->name('admin.encadreurs.store');
    Route::put('/admin/encadreurs/{encadreur}', [AdminController::class, 'updateEncadreur'])->name('admin.encadreurs.update');
    Route::delete('/admin/encadreurs/{encadreur}', [AdminController::class, 'deleteEncadreur'])->name('admin.encadreurs.delete');
    
    // Badge generation
    Route::post('/admin/generate-all-badges', [AdminController::class, 'generateAllBadges'])->name('admin.generate.all.badges');
    Route::post('/admin/generate-missing-badges', [AdminController::class, 'generateMissingBadges'])->name('admin.generate.missing.badges');
    
    // Student management
    Route::resource('etudiants', EtudiantController::class);
    Route::get('/etudiants/{etudiant}/download-badge', [EtudiantController::class, 'downloadBadge'])->name('etudiants.download.badge');
    Route::get('/etudiants/{etudiant}/preview-badge', [EtudiantController::class, 'previewBadge'])->name('etudiants.preview.badge');
    Route::post('/etudiants/{etudiant}/regenerate-badge', [EtudiantController::class, 'regenerateBadge'])->name('etudiants.regenerate.badge');
    Route::get('/etudiants/get-sections', [EtudiantController::class, 'getSections'])->name('etudiants.get.sections');
});
