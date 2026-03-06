<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnLoanController;
use Illuminate\Support\Facades\Route;

Route::post('v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Auth

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);

    // Books

    Route::get('books', [BookController::class, 'index']);        // listar libros
    Route::get('books/{book}', [BookController::class, 'show']);  // ver libro
    Route::post('books', [BookController::class, 'store']);       // crear libro
    Route::put('books/{book}', [BookController::class, 'update']); // actualizar
    Route::delete('books/{book}', [BookController::class, 'destroy']); // eliminar

    //Loans

    Route::get('loans', [LoanController::class, 'index']);           // ver prestamos
    Route::post('loans', [LoanController::class, 'store']);          // pedir libro
    Route::post('loans/{loan}/return', ReturnLoanController::class); // devolver libro
    Route::get('loans/history', [LoanController::class, 'history']); // historial
});