<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SettingController;


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::get('/verified', [App\Http\Controllers\HomeController::class, 'verified'])->name('verified');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/name-email', [ProfileController::class, 'updateNameAndEmail'])->name('profile.updateNameAndEmail');
Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
Route::post('/profile/user-password', [ProfileController::class, 'updateUserAndPassword'])->name('profile.updateUserAndPassword');

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::get('sales', [SaleController::class, 'adminIndex'])->name('sales.index');

});

Route::middleware(['auth', SuperAdminMiddleware::class])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('sales', [SaleController::class, 'adminIndex'])->name('sales.index');
});


// WALLAPOP
Route::middleware(['auth'])->group(function () {
    Route::resource('sales', SaleController::class);
    
    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('settings', SettingController::class);
    });
});




Route::get('private-storage/{path}', function ($path) {
    if (Auth::check()) {
        try {
            // Get full path from the private storage products directory
            $fullPath = 'products/' . $path;
            
            // Check if file exists
            if (!Storage::disk('private')->exists($fullPath)) {
                abort(404);
            }
            
            // Return file response with proper content type
            return Storage::disk('private')->response($fullPath, null, [
                'Cache-Control' => 'private, max-age=3600'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error serving private file: ' . $e->getMessage());
            abort(500);
        }
    }
    abort(403);
})->where('path', '.*')->name('private.storage');