<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Penerbit\ImportBukuController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProdiController;
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

  Route::get('/users/inactive', [SuperAdminController::class, 'inactive_users'])->name('inactive_users');
  Route::post('/users/inactive', [SuperAdminController::class, 'inactive_users']);

  Route::get('/roles', [SuperAdminController::class, 'roles'])->name('roles');
  Route::post('/roles', [SuperAdminController::class, 'roles']);

  Route::get('/campuses', [SuperAdminController::class, 'campuses'])->name('campuses');
  Route::post('/campuses', [SuperAdminController::class, 'campuses']);

  Route::get('/majors', [SuperAdminController::class, 'majors'])->name('majors');
  Route::post('/majors', [SuperAdminController::class, 'majors']);

  Route::get('/publisher', [SuperAdminController::class, 'publisher'])->name('publisher');
  Route::post('/publisher', [SuperAdminController::class, 'publisher']);

  Route::get('/pengadaan/baru', [SuperAdminController::class, 'new_procurements'])->name('procurements.new');
  Route::post('/pengadaan/baru', [SuperAdminController::class, 'new_procurements']);

  Route::get('/pengadaan/{invoice}/books', [SuperAdminController::class, 'books_procurements'])->name('procurements.books');
  Route::post('/pengadaan/{invoice}/books', [SuperAdminController::class, 'books_procurements']);

  Route::get('/pengadaan/aktif', [SuperAdminController::class, 'active_procurements'])->name('procurements.active');
  Route::post('/pengadaan/aktif', [SuperAdminController::class, 'active_procurements']);

  Route::get('/pengadaan/arsip', [SuperAdminController::class, 'archived_procurements'])->name('procurements.archived');
  Route::post('/pengadaan/arsip', [SuperAdminController::class, 'archived_procurements']);

  Route::get('/pengadaan/{id}/approve', [SuperAdminController::class, 'procurement_approve'])->name('procurement.approve');
  Route::get('/pengadaan/{id}/reject', [SuperAdminController::class, 'procurement_reject'])->name('procurement.reject');
  Route::get('/pengadaan/{id}/verify', [SuperAdminController::class, 'procurement_verify'])->name('procurement.verify');
});

Route::prefix('penerbit')->middleware(['role:Penerbit', 'auth'])->group(function () {
  Route::get('/pengadaan', [PenerbitController::class, 'invoices'])->name('penerbit.invoices');
  Route::post('/pengadaan', [PenerbitController::class, 'invoices']);

  Route::get('/pengadaan/{invoice}/books', [PenerbitController::class, 'books'])->name('penerbit.invoices.books');
  Route::post('/pengadaan/{invoice}/books', [PenerbitController::class, 'books']);

  Route::post('/pengadaan/{invoice}/import', ImportBukuController::class)->name('penerbit.invoices.books.import');

  Route::get('/pengadaan/aktif', [PenerbitController::class, 'ongoing_invoices'])->name('penerbit.invoices.ongoing');
  Route::post('/pengadaan/aktif', [PenerbitController::class, 'ongoing_invoices']);

  Route::get('/pengadaan/aktif/{invoice}/books', [PenerbitController::class, 'ongoing_books'])->name('penerbit.invoices.books.ongoing');
  Route::post('/pengadaan/aktif/{invoice}/books', [PenerbitController::class, 'ongoing_books']);

  Route::get('/pengadaan/arsip', [PenerbitController::class, 'verified_invoices'])->name('penerbit.invoices.verified');
  Route::post('/pengadaan/arsip', [PenerbitController::class, 'verified_invoices']);

  Route::get('/pengadaan/arsip/{invoice}/books', [PenerbitController::class, 'verified_books'])->name('penerbit.invoices.books.verified');
  Route::post('/pengadaan/arsip/{invoice}/books', [PenerbitController::class, 'verified_books']);
});

Route::prefix('prodi')->middleware(['role:Admin Prodi', 'auth'])->group(function () {
    Route::get('/pengadaan/aktif', [ProdiController::class, 'active_procurements'])->name('prodi.procurements.active');
    Route::post('/pengadaan/aktif', [ProdiController::class, 'active_procurements']);

    Route::get('/pengadaan/aktif/{invoice}/books', [ProdiController::class, 'procurement_books'])->name('prodi.procurements.books.active');
    Route::post('/pengadaan/aktif/{invoice}/books', [ProdiController::class, 'procurement_books']);

    Route::get('/pengadaan/arsip', [ProdiController::class, 'archived_procurements'])->name('prodi.procurements.archived');
    Route::post('/pengadaan/arsip', [ProdiController::class, 'archived_procurements']);
});

Route::middleware('auth')->group(function() {
    Route::get('/profile',[ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profile', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/profile/password', [ProfilController::class, 'password'])->name('profil.password');
    Route::patch('/profile/password', [ProfilController::class, 'password'])->name('profil.update-password');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * socialite auth
 */
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);
