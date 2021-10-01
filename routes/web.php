<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('category', 'App\Http\Controllers\CategoryController');

    Route::resource('product', 'App\Http\Controllers\ProductController');    
    
    Route::resource('company', 'App\Http\Controllers\CompanyController');
    
    Route::resource('employee', 'App\Http\Controllers\EmployeeController');

});