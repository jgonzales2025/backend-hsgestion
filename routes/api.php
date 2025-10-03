<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\User\Infrastructure\Controllers\UserController;
use App\Modules\Auth\Infrastructure\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CompanyController;

Route::get('/users/{id}', [UserController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/roles', [RoleController::class, 'index']);

Route::get('/usernames', [UserController::class, 'findAllUserName']);

Route::get('/users', [UserController::class, 'findAllUsers']);

Route::get('/companies', [CompanyController::class, 'index']);
