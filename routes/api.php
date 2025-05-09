<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    //User api route
    Route::get('/api/users', [UserController::class, 'getUsers']);
    Route::get('/api/users/getUserByID/{id}', [UserController::class, 'getUserByID']);
    Route::post('/api/users/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::post('/api/users/delete/{id}', [UserController::class, 'deleteUser']);
    Route::post('/api/users/blockUser/{id}', [UserController::class, 'blockUser']);
    Route::post('/users/addUser', [UserController::class, 'addUsers']);
    Route::post('/api/users/deleteSelectedUsers', [UserController::class, 'deleteMultipleUsers']);

    //Customer api route
    Route::post('api/customers/addCustomer', [CustomerController::class, 'addCustomer']);
    Route::post('api/customers/updateCustomer/{id}', [CustomerController::class, 'updateCustomer']);
    Route::post('/customers/import', [CustomerController::class, 'importExcel']);
    Route::get('/customers/export', [CustomerController::class, 'exportExcel']);


    //Product api route
    Route::post('api/products/addProduct', [ProductController::class, 'addProduct']);
    Route::post('api/products/updateProduct', [ProductController::class, 'updateProduct']);
    Route::post('/api/products/delete/{id}', [ProductController::class, 'deleteProduct']);
});