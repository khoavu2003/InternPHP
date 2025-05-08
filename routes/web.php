<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});
//Authentication route



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// load view user
Route::get('/userManager', [UserController::class, 'showUserManager'])->name('user.manager');

//load view customer
Route::get('/customerManager', [CustomerController::class, 'showCustomerManager'])->name('customer.manager');
// Load view Product
Route::get('/productManager', [ProductController::class, 'showProductManager'])->name('product.manager');
Route::get('/productManager/productDetail', [ProductController::class, 'showProductDetail'])->name('product.detail');
Route::get('/productManager/productDetail/{product_id}', [ProductController::class, 'showProductDetail']);
Route::get('/api/users/searchUser', [UserController::class, 'searchUser']);

//Customer api route
Route::get('/api/customers/searchCustomer', [CustomerController::class, 'searchCustomer']);
Route::get('/api/products/searchProduct', [ProductController::class, 'searchProduct']);

Route::middleware(['auth'])->group(function () {
    //User api route
    Route::get('/api/users', [UserController::class, 'getUsers']);
    Route::get('/api/users/getUserByID/{id}', [UserController::class, 'getUserByID']);
    Route::post('/api/users/updateUser/{id}', [UserController::class, 'updateUser']);
    Route::post('/api/users/delete/{id}', [UserController::class, 'deleteUser']);
    Route::post('/api/users/blockUser/{id}', [UserController::class, 'blockUser']);
    Route::post('/api/users/addUser', [UserController::class, 'addUsers']);
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
