<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['setLocale'])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/set-locale', [LanguageController::class, 'setLocale'])->name('set.locale');

    Route::middleware(['auth', 'isAdmin'])->group(function () {
        Route::get('/companies/datatable', [CompanyController::class, 'index'])->name('companies.datatable');
        Route::get('/employees/datatable', [EmployeeController::class, 'index'])->name('employees.datatable');
        Route::resource('companies', CompanyController::class);
        Route::resource('employees', EmployeeController::class);
        Route::post('/files', [FileController::class, 'store_image']);
    });

});