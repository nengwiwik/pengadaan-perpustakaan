<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Penerbit\ImportBukuController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ProfilController;
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
  return view('welcome', [
    'type_menu' => null
  ]);
})->middleware('auth')->name('homepage');

Route::get('/logout', function () {
  Auth::logout();
  return to_route('login');
})->middleware('auth');

Route::prefix('admin')->middleware('auth')->group(function () {
  Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');
  Route::post('/permissions', [PermissionController::class, 'permissions']);

  Route::get('/users/prodi', [SuperAdminController::class, 'prodi_users'])->name('prodi_users');
  Route::post('/users/prodi', [SuperAdminController::class, 'prodi_users']);

  Route::get('/users/publisher', [SuperAdminController::class, 'publisher_users'])->name('publisher_users');
  Route::post('/users/publisher', [SuperAdminController::class, 'publisher_users']);

  Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
  Route::post('/roles', [SuperAdminController::class, 'roles']);

  Route::get('/campuses', [SuperAdminController::class, 'campuses'])->name('campuses');
  Route::post('/campuses', [SuperAdminController::class, 'campuses']);

  Route::get('/majors', [SuperAdminController::class, 'majors'])->name('majors');
  Route::post('/majors', [SuperAdminController::class, 'majors']);

  Route::get('/publisher', [SuperAdminController::class, 'publisher'])->name('publisher');
  Route::post('/publisher', [SuperAdminController::class, 'publisher']);

  Route::get('/procurements', [SuperAdminController::class, 'procurements'])->name('procurements');
  Route::post('/procurements', [SuperAdminController::class, 'procurements']);
  Route::get('/procurements/{id}/approve', [SuperAdminController::class, 'procurement_approve'])->name('procurement.approve');
  Route::get('/procurements/{id}/reject', [SuperAdminController::class, 'procurement_reject'])->name('procurement.reject');
  Route::get('/procurements/{id}/verify', [SuperAdminController::class, 'procurement_verify'])->name('procurement.verify');
});

Route::prefix('penerbit')->middleware(['role:Penerbit'])->group(function () {
  Route::get('/invoices', [PenerbitController::class, 'invoices'])->name('penerbit.invoices');
  Route::post('/invoices', [PenerbitController::class, 'invoices']);

  Route::get('/invoices/{invoice}/books', [PenerbitController::class, 'books'])->name('penerbit.invoices.books');
  Route::post('/invoices/{invoice}/books', [PenerbitController::class, 'books']);

  Route::post('/invoices/{invoice}/import', ImportBukuController::class)->name('penerbit.invoices.books.import');

  Route::get('/ongoing-invoices', [PenerbitController::class, 'ongoing_invoices'])->name('penerbit.invoices.ongoing');
  Route::post('/ongoing-invoices', [PenerbitController::class, 'ongoing_invoices']);

  Route::get('/ongoing-invoices/{invoice}/books', [PenerbitController::class, 'ongoing_books'])->name('penerbit.invoices.books.ongoing');
  Route::post('/ongoing-invoices/{invoice}/books', [PenerbitController::class, 'ongoing_books']);

  Route::get('/verified-invoices', [PenerbitController::class, 'verified_invoices'])->name('penerbit.invoices.verified');
  Route::post('/verified-invoices', [PenerbitController::class, 'verified_invoices']);

  Route::get('/verified-invoices/{invoice}/books', [PenerbitController::class, 'verified_books'])->name('penerbit.invoices.books.verified');
  Route::post('/verified-invoices/{invoice}/books', [PenerbitController::class, 'verified_books']);
});

Route::get('/profile',[ProfilController::class, 'index'])->name('profil.index');
Route::patch('/profile', [ProfilController::class, 'update'])->name('profil.update');
Route::get('/profile/password', [ProfilController::class, 'password'])->name('profil.password');
Route::patch('/profile/password', [ProfilController::class, 'password'])->name('profil.update-password');

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * socialite auth
 */
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
