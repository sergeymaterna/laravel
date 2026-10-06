<?php

use App\Http\Controllers\Api\V1\PostApiController;
use Illuminate\Support\Facades\Route;

// Створить тільки 5 API-маршрутів (без create та edit)
Route::apiResource('v1/posts', PostApiController::class)->names('api.v1.posts');

Route::post('v1/login', [AuthApiController::class, 'login']);

// Захищені роути (вимагають Header: Authorization: Bearer <your_token>)
Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
	Route::post('logout', [AuthApiController::class, 'logout']);
	Route::apiResource('posts', PostApiController::class);
});