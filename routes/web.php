<?php

use App\Http\Controllers\GroceryCrud\PermissionController;
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
  Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');
  Route::post('/permissions', [PermissionController::class, 'permissions']);
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::post('/users', [SuperAdminController::class, 'users']);

  Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
  Route::post('/roles', [SuperAdminController::class, 'roles']);

  Route::get('/campuses', [SuperAdminController::class, 'campuses'])->name('campuses');
  Route::post('/campuses', [SuperAdminController::class, 'campuses']);

  Route::get('/majors', [SuperAdminController::class, 'majors'])->name('majors');
  Route::post('/majors', [SuperAdminController::class, 'majors']);

  Route::get('/publisher', [SuperAdminController::class, 'publisher'])->name('publisher');
  Route::post('/publisher', [SuperAdminController::class, 'publisher']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
