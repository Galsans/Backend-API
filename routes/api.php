<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentControlller;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UserContoller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentikasi
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
});

// REST History
Route::post('history/{barangId}', [HistoryController::class, 'store']);
Route::get('history/{barangId}', [HistoryController::class, 'index']);

// REST Category
Route::get('category', [CategoryController::class, 'index']);
Route::post('category', [CategoryController::class, 'store']);
Route::put('category/{id}', [CategoryController::class, 'update']);
Route::delete('category/{id}', [CategoryController::class, 'destroy']);
Route::get('category/restore/{id}', [CategoryController::class, 'restore']);

// REST Barang
Route::get('barang', [BarangController::class, 'index']);
Route::post('barang', [BarangController::class, 'store']);
Route::put('barang/{id}', [BarangController::class, 'update']);
Route::delete('barang/{id}', [BarangController::class, 'destroy']);
Route::get('barang/search/{asset_id}', [BarangController::class, 'search']);
Route::get('barang/restore/{id}', [BarangController::class, 'restore']);

// REST Divisi
Route::get('divisi', [DivisiController::class, 'index']);
Route::post('divisi', [DivisiController::class, 'store']);
Route::put('divisi/{id}', [DivisiController::class, 'update']);
Route::delete('divisi/{id}', [DivisiController::class, 'destroy']);
Route::get('divisi/restore/{id}', [DivisiController::class, 'restore']);

// REST Department
Route::get('department', [DepartmentControlller::class, 'index']);
Route::post('department', [DepartmentControlller::class, 'store']);
Route::put('department/{id}', [DepartmentControlller::class, 'update']);
Route::delete('department/{id}', [DepartmentControlller::class, 'destroy']);
Route::get('department/restore/{id}', [DepartmentControlller::class, 'restore']);


// CRUD User role admin or user
Route::get('user', [UserContoller::class, 'index']);
Route::post('user', [UserContoller::class, 'store']);
Route::put('user/{id}', [UserContoller::class, 'update']);
Route::delete('user/{id}', [UserContoller::class, 'delete']);
Route::get('user/restore/{id}', [UserContoller::class, 'restore']);
