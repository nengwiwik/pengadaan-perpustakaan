<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(Auth::id());
        // untuk super admin
        if ($user->hasRole(['Super Admin'])) {
            $jumlah_pengadaan_baru = Invoice::where('status', Invoice::STATUS_BARU)->count();
            $jumlah_pengadaan_aktif = Invoice::where('status', Invoice::STATUS_AKTIF)->count();
            $jumlah_arsip_pengadaan = Invoice::whereIn('status', [Invoice::STATUS_SELESAI, Invoice::STATUS_DITOLAK])->count();
            $jumlah_penerbit = Publisher::count();
        }

        // untuk penerbit
        if ($user->hasRole(['Penerbit'])) {
            $jumlah_pengadaan_baru = Invoice::where('publisher_id', $user->publisher_id)->where('status', Invoice::STATUS_BARU)->count();
            $jumlah_pengadaan_aktif = Invoice::where('publisher_id', $user->publisher_id)->where('status', Invoice::STATUS_AKTIF)->count();
            $jumlah_arsip_pengadaan = Invoice::where('publisher_id', $user->publisher_id)->whereIn('status', [Invoice::STATUS_SELESAI, Invoice::STATUS_DITOLAK])->count();
            $jumlah_penerbit = Publisher::count();
        }
        return view('welcome', [
            'type_menu' => null
        ], compact([
            'jumlah_pengadaan_baru',
            'jumlah_pengadaan_aktif',
            'jumlah_arsip_pengadaan',
            'jumlah_penerbit',
        ]));
    }
}
