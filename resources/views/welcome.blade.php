@extends('layouts.admin')

@section('content')
  <h1 class="h3 mb-4 text-gray-800">UNDIRA</h1>

  @hasanyrole('Super Admin|Penerbit|Admin Prodi')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Selamat datang, {{ auth()->user()->name }}!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="row">

      <!-- Pengadaan Baru Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  <a href="{{ route('procurements.new') }}">Pengadaan Baru</a>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_pengadaan_baru }}</div>
              </div>
              <div class="col-auto">
                <a href="{{ route('procurements.new') }}"><i class="fas fa-bell fa-2x text-gray-300"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pengadaan Aktif Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  <a href="{{ route('procurements.active') }}">Pengadaan Aktif</a>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_pengadaan_aktif }}</div>
              </div>
              <div class="col-auto">
                <a href="{{ route('procurements.active') }}"><i class="fas fa-clock fa-2x text-gray-300"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  <a href="{{ route('procurements.archived') }}">Arsip Pengadaan</a>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_arsip_pengadaan }}</div>
              </div>
              <div class="col-auto">
                <a href="{{ route('procurements.archived') }}"><i class="fas fa-archive fa-2x text-gray-300"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Penerbit Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  <a href="{{ route('publisher') }}">Penerbit</a>
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_penerbit }}</div>
              </div>
              <div class="col-auto">
                <a href="{{ route('publisher') }}"><i class="fas fa-users fa-2x text-gray-300"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="alert alert-danger" role="alert">
      Akun Anda belum aktif. Silahkan hubungi Administrator dahulu.
    </div>
  @endhasanyrole
@endsection
