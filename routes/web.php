<?php

use App\Http\Controllers\GroceryCrud\SuperAdminController;
use Illuminate\Support\Facades\Auth;
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
})->middleware('auth');

Route::get('/logout', function () {
    Auth::logout();
    return to_route('login');
})->middleware('auth');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::post('/users', [SuperAdminController::class, 'users']);
    Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
    Route::post('/roles', [SuperAdminController::class, 'roles']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
