<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class)->names('posts');


//Route::get('/posts/top', [PostController::class, 'top']);
//Route::post('/posts/{post}/publish', [PostController::class, 'publish']);
//Route::delete('/posts/{post}/archive', [PostController::class, 'archive']);
//Route::get('/posts/top', [PostController::class, 'top'])->name('posts.top');
//Route::view('/about', 'pages.about');