<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Registro sin necesidad de estar autenticado.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Productos
Route::get('/products', [ProductController::class, 'index']); // Listar todos los productos

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //Productos
    Route::post('/products', [ProductController::class, 'store']);
    // Listar todos los productos
    Route::get('/products/{id}', [ProductController::class, 'show']); // Ver un producto
    Route::put('/products/{id}', [ProductController::class, 'update']); // Actualizar
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Eliminar
    //Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirm']);
});


