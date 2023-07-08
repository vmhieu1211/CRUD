<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;




Route::get('/login', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [LoginController::class, 'loginSubmit'])->name('login.submit');


Route::group(['middleware' => 'CheckLogout'], function () {
    Route::get('/logout', [LoginController::class, 'loginForm'])->name('loginForm');
    Route::post('/logout', [LoginController::class, 'logoutSubmit'])->name('logoutSubmit');
});

Route::middleware(['CheckLogin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::get('/search', [ProductController::class, 'search'])->name('products.search');

Route::get('/send-mail', function () {
    $user = App\Models\User::find(8);
    Mail::to($user->email)->send(new WelcomeMail($user));
    return "Email sent successfully";
});
