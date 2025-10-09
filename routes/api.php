<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\MenuController;
use App\Modules\Brand\Infrastructure\Controllers\BrandController;
use App\Modules\Category\Infrastructure\Controllers\CategoryController;
use App\Modules\TransportCompany\Infrastructure\Controllers\TransportCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\User\Infrastructure\Controllers\UserController;
use App\Modules\Auth\Infrastructure\Controllers\AuthController;
use App\Modules\Driver\Infrastructure\Controllers\DriverController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerDocumentTypeController;
use App\Modules\SubCategory\Infrastructure\Controllers\SubCategoryController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::get('/permissions', [RoleController::class, 'indexPermissions']);

Route::get('/usernames', [UserController::class, 'findAllUserName']);

Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);
Route::get('/companies-user', [CompanyController::class, 'indexByUser']);

Route::get('/branches', [BranchController::class, 'index']);

Route::get('/menus', [MenuController::class, 'index']);

// User routes devuelvelo como estaba protegido
Route::get('/users', [UserController::class, 'findAllUsers']);
Route::get('/users-vendedor', [UserController::class, 'findAllUsersByVendedor']);
Route::get('/users-almacen', [UserController::class, 'findAllUsersByAlmacen']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);


// TIPOS DE DOCUMENTOS (DNI, RUC, ETC)
Route::get('driver-document-types', [CustomerDocumentTypeController::class, 'indexForDrivers']);

// Marcas
Route::get('brands', [BrandController::class, 'index']);
Route::post('brands', [BrandController::class, 'store']);
Route::get('brands/{id}', [BrandController::class, 'show']);
Route::put('brands/{id}', [BrandController::class, 'update']);

// Drivers - conductores
Route::get('drivers',[DriverController::class, 'index']);
Route::post('drivers',[DriverController::class, 'store']);
Route::get('drivers/{id}',[DriverController::class, 'show']);
Route::put('drivers/{id}',[DriverController::class, 'update']);

// Categories - categorias
Route::get('categories',[CategoryController::class, 'index']);
Route::post('categories',[CategoryController::class, 'store']);
Route::get('categories/{id}',[CategoryController::class, 'show']);
Route::put('categories/{id}',[CategoryController::class, 'update']);

// SubCategories - subcategorias
Route::get('sub-categories',[SubCategoryController::class, 'index']);
Route::post('sub-categories',[SubCategoryController::class, 'store']);
Route::get('sub-categories/{id}',[SubCategoryController::class, 'show']);
Route::put('sub-categories/{id}',[SubCategoryController::class, 'update']);

// TransportCompanies - Empresa de transportes
Route::get('transport-companies',[TransportCompanyController::class, 'index']);
Route::post('transport-companies',[TransportCompanyController::class, 'store']);
Route::get('transport-companies/{id}',[TransportCompanyController::class, 'show']);
Route::put('transport-companies/{id}',[TransportCompanyController::class, 'update']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

});

