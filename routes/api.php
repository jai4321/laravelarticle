<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EncryptMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', LoginController::class);
Route::post('register', RegisterController::class);
Route::middleware(["auth:sanctum"])->group(function () {
    Route::apiResource('articles', ArticleController::class);
    Route::match(["post", "put"],'encrypt', [ArticleController::class,'encrypt'])->middleware(EncryptMiddleware::class);
    Route::post('decrypt', [ArticleController::class,'decrypt']);
});