<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use App\Models\Procurement;
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

        if (!$user->hasAnyRole(['Super Admin', 'Penerbit', 'Admin Prodi'])) {
            Auth::logout();
            return to_route('login')->with(["error" => "Your account is created succesfully but not activated yet."]);
        }

        // default
        $jumlah_pengadaan_baru = 0;
        $jumlah_pengadaan_aktif = 0;
        $jumlah_arsip_pengadaan = 0;
        $jumlah_penerbit = 0;
        $jumlah_user_belum_aktif = 0;
        $jumlah_request_buku = 0;

        $route_pengadaan_baru = "#";
        $route_pengadaan_aktif = "#";
        $route_arsip_pengadaan = "#";
        $route_jumlah_penerbit = "#";

        $nominal_pengadaan_aktif = 0;
        $nominal_arsip_pengadaan = 0;

        // untuk super admin
        if ($user->hasExactRoles('Super Admin')) {
            $jumlah_pengadaan_baru = Procurement::where('status', Procurement::STATUS_BARU)->count();
            $jumlah_pengadaan_aktif = Procurement::where('status', Procurement::STATUS_AKTIF)->count();
            $jumlah_arsip_pengadaan = Procurement::whereIn('status', [Procurement::STATUS_INVOICE, Procurement::STATUS_SELESAI, Procurement::STATUS_DITOLAK])->count();
            $jumlah_penerbit = Publisher::count();
            $jumlah_user_belum_aktif = User::doesntHave('roles')->count();
            $jumlah_request_buku = BookRequest::where('status', BookRequest::STATUS_REQUESTED)->count();

            $route_pengadaan_baru = route('procurements.new');
            $route_pengadaan_aktif = route('procurements.active');
            $route_arsip_pengadaan = route('procurements.archived');
            $route_jumlah_penerbit = route('publisher');

            $nominal_pengadaan_aktif = Procurement::where('status', Procurement::STATUS_AKTIF)->sum('total_price');
            $nominal_arsip_pengadaan = Procurement::whereIn('status', [Procurement::STATUS_SELESAI, Procurement::STATUS_INVOICE])->sum('total_price');
        }

        // untuk prodi
        if ($user->hasExactRoles('Admin Prodi')) {
            $jumlah_pengadaan_baru = Procurement::where('campus_id', $user->campus_id)->where('status', Procurement::STATUS_BARU)->count();
            $jumlah_pengadaan_aktif = Procurement::where('campus_id', $user->campus_id)->where('status', Procurement::STATUS_AKTIF)->count();
            $jumlah_arsip_pengadaan = Procurement::where('campus_id', $user->campus_id)->whereIn('status', [Procurement::STATUS_SELESAI, Procurement::STATUS_DITOLAK, Procurement::STATUS_INVOICE])->count();
            $jumlah_penerbit = Procurement::where('campus_id', $user->campus_id)->count();

            $route_pengadaan_aktif = route('prodi.procurements.active');
            $route_arsip_pengadaan = route('prodi.procurements.archived');
        }

        // untuk penerbit
        if ($user->hasExactRoles('Penerbit')) {
            $jumlah_pengadaan_baru = Procurement::where('publisher_id', $user->publisher_id)->where('status', Procurement::STATUS_PROSES)->count();
            $jumlah_pengadaan_aktif = Procurement::where('publisher_id', $user->publisher_id)->whereIn('status', [Procurement::STATUS_BARU, Procurement::STATUS_AKTIF])->count();
            $jumlah_arsip_pengadaan = Procurement::where('publisher_id', $user->publisher_id)->whereIn('status', [Procurement::STATUS_SELESAI, Procurement::STATUS_DITOLAK, Procurement::STATUS_INVOICE])->count();
            $jumlah_penerbit = Procurement::where('publisher_id', $user->publisher_id)->count();

            $route_pengadaan_baru = route('penerbit.procurements');
            $route_pengadaan_aktif = route('penerbit.procurements.ongoing');
            $route_arsip_pengadaan = route('penerbit.procurements.verified');

            $nominal_pengadaan_aktif = Procurement::where('publisher_id', $user->publisher_id)->where('status', Procurement::STATUS_AKTIF)->sum('total_price');
            $nominal_arsip_pengadaan = Procurement::where('publisher_id', $user->publisher_id)->whereIn('status', [Procurement::STATUS_SELESAI, Procurement::STATUS_INVOICE])->sum('total_price');
        }

        $nominal_pengadaan_aktif = "IDR " . number_format($nominal_pengadaan_aktif, 0, ',', '.');
        $nominal_arsip_pengadaan = "IDR " . number_format($nominal_arsip_pengadaan, 0, ',', '.');

        return view('welcome', ['type_menu' => null], compact([
            'jumlah_pengadaan_baru',
            'jumlah_pengadaan_aktif',
            'jumlah_arsip_pengadaan',
            'jumlah_penerbit',
            'jumlah_user_belum_aktif',
            'jumlah_request_buku',
            'route_pengadaan_baru',
            'route_pengadaan_aktif',
            'route_arsip_pengadaan',
            'route_jumlah_penerbit',
            'nominal_pengadaan_aktif',
            'nominal_arsip_pengadaan',
        ]));
    }
}
