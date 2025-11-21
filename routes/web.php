<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;

// ------------------------------------
// Public: complaint submission (home)
// ------------------------------------
Route::get('/', [ComplaintController::class, 'create'])
    ->name('complaints.create');

Route::post('/complaints', [ComplaintController::class, 'store'])
    ->name('complaints.store');

// ------------------------------------
// Dashboard route used by Breeze after login
// ------------------------------------
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->is_admin) {
        // admin goes to admin dashboard
        return redirect()->route('admin.index');
    }

    // normal user goes to complaint form
    return redirect()->route('complaints.create');
})->middleware(['auth'])->name('dashboard');

// ------------------------------------
// Admin routes: protected by auth + AdminMiddleware
// ------------------------------------
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');

    Route::get('/admin/complaint/{id}', [AdminController::class, 'show'])
        ->name('admin.show');

    Route::post('/admin/complaint/{id}/resolve', [AdminController::class, 'resolve'])
        ->name('admin.resolve');

    Route::delete('/admin/complaint/{id}', [AdminController::class, 'destroy'])
        ->name('admin.destroy');

    Route::post('/admin/complaint/{id}/note', [AdminController::class, 'addNote'])->name('admin.addNote');

});

// ------------------------------------
// Breeze profile routes (optional)
// ------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------------------------
// Breeze auth routes (login/register/etc.)
// ------------------------------------
require __DIR__.'/auth.php';
