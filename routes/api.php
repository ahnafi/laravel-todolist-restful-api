<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/users/register", [\App\Http\Controllers\AuthController::class, 'register'])->name("register");
Route::post("/users/login", [\App\Http\Controllers\AuthController::class, "login"])->name("login");

Route::middleware("auth:sanctum")->group(function () {

    Route::get("/users/current", [\App\Http\Controllers\UserController::class, "get"]);
    Route::patch("/users/current", [\App\Http\Controllers\UserController::class, 'update']);
    Route::post("/users/logout", [\App\Http\Controllers\AuthController::class, "logout"])->name("logout");

    Route::post("/tasks", [\App\Http\Controllers\TaskController::class, "create"]);
    Route::get("/tasks/{id}", [\App\Http\Controllers\TaskController::class, "get"])->where("id", "[0-9]+");
    Route::patch("/tasks/{id}", [\App\Http\Controllers\TaskController::class, "update"])->where("id", "[0-9]+");
    Route::delete("/tasks/{id}", [\App\Http\Controllers\TaskController::class, "remove"])->where("id", "[0-9]+");

});
