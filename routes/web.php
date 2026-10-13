<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class)->names('posts');


// Гостьові маршрути
Route::middleware('guest')->group(function () {
	fn() => view('auth.login')->name('login'); // або через контролер
	Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login', [AuthController::class, 'login']);

	Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
	Route::post('/register', [AuthController::class, 'register']);
});

// Захищені маршрути для адміністрування юзерів
Route::middleware('auth:sanctum')->group(function () {
	Route::get('/users', [UserController::class, 'index'])->name('users.index');
	Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
	Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//Route::get('/posts/top', [PostController::class, 'top']);
//Route::post('/posts/{post}/publish', [PostController::class, 'publish']);
//Route::delete('/posts/{post}/archive', [PostController::class, 'archive']);
//Route::get('/posts/top', [PostController::class, 'top'])->name('posts.top');
//Route::view('/about', 'pages.about');