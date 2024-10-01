<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('login', LoginController::class);
Route::post('register', RegisterController::class);
Route::middleware(["auth:sanctum"])->group(function () {
    Route::apiResource('articles', ArticleController::class);
});