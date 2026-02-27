<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LanguageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::middleware([SetLocale::class])->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/set-locale', [LanguageController::class, 'setLocale'])->name('set.locale');

    Route::middleware(['auth'])->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::resource('employees', EmployeeController::class);
        Route::post('/files', [FileController::class, 'store_image']);
        Route::get('/companies/datatable', [CompanyController::class, 'datatable'])->name('companies.datatable');
    });

});