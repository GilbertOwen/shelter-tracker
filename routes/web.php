<?php

use App\Http\Controllers\Admin\AdminDogController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AssignCaretakerController;
use App\Http\Controllers\Admin\ContactTraceController;
use App\Http\Controllers\Caretaker\CaretakerDogController;
use App\Http\Controllers\Caretaker\CaretakerScheduleController;
use App\Http\Controllers\Caretaker\ContactLogController;
use App\Http\Controllers\Caretaker\HealthRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicDogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicDogController::class, 'index'])->name('home');

Route::get('/adopt', [PublicDogController::class, 'index'])->name('adopt.index');
Route::get('/adopt/{dog}', [PublicDogController::class, 'show'])->name('adopt.show');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('dogs', AdminDogController::class)->except(['show']);
        Route::post('/dogs/{dog}/assign', AssignCaretakerController::class)->name('dogs.assign');
        Route::resource('users', AdminUserController::class)->except(['show', 'destroy']);
        Route::resource('schedules', AdminScheduleController::class)->except(['show']);
        Route::get('/contact-trace', [ContactTraceController::class, 'index'])->name('contact-trace');
    });

Route::middleware(['auth', 'role:caretaker'])
    ->prefix('caretaker')
    ->name('caretaker.')
    ->group(function () {
        Route::get('/dogs', [CaretakerDogController::class, 'index'])->name('dogs.index');
        Route::get('/dogs/{dog}', [CaretakerDogController::class, 'show'])->name('dogs.show');
        Route::get('/schedules', [CaretakerScheduleController::class, 'index'])->name('schedules.index');
        Route::patch('/schedules/{schedule}/complete', [CaretakerScheduleController::class, 'complete'])->name('schedules.complete');
        Route::resource('health-records', HealthRecordController::class)->only(['create', 'store']);
        Route::resource('contact-log', ContactLogController::class)->only(['index', 'create', 'store']);
    });

require __DIR__.'/auth.php';
