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

      @hasanyrole('Super Admin|Penerbit')
        {{-- List Pengadaan Baru --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    <a href="{{ $route_pengadaan_baru }}" class="stretched-link text-reset text-decoration-none">Pengadaan
                      Baru</a>
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_pengadaan_baru }}</div>
                </div>
                <div class="col-auto">
                  <a href="{{ $route_pengadaan_baru }}"><i class="fas fa-bell fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endhasanyrole

      @hasanyrole('Super Admin|Penerbit|Admin Prodi')
        {{-- List Pengadaan Aktif --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    <a href="{{ $route_pengadaan_aktif }}" class="stretched-link text-reset text-decoration-none">Pengadaan
                      Aktif</a>
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_pengadaan_aktif }}</div>
                </div>
                <div class="col-auto">
                  <a href="{{ $route_pengadaan_aktif }}"><i class="fas fa-clock fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endhasanyrole

      @hasanyrole('Super Admin|Penerbit|Admin Prodi')
        {{-- List Arsip Pengadaan --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    <a href="{{ $route_arsip_pengadaan }}" class="stretched-link text-reset text-decoration-none">Arsip
                      Pengadaan</a>
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_arsip_pengadaan }}</div>
                </div>
                <div class="col-auto">
                  <a href="{{ $route_arsip_pengadaan }}"><i class="fas fa-archive fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endhasanyrole

      @role('Penerbit')
        {{-- List Total Pengadaan --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    Total Pengadaan
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlah_penerbit }}</div>
                </div>
                <div class="col-auto">
                  <i class="fas fa-plus fa-2x text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endrole

      @role('Super Admin')
        {{-- List Total Penerbit --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                    <a href="{{ route('publisher') }}" class="stretched-link text-reset text-decoration-none">Penerbit</a>
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
      @endrole

    </div>

    <div class="row">

      @hasanyrole('Super Admin|Penerbit|Admin Prodi')
        {{-- Nominal Pengadaan Aktif --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    <a href="{{ $route_pengadaan_aktif }}" class="stretched-link text-reset text-decoration-none">Pengadaan Aktif</a>
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $nominal_pengadaan_aktif }}</div>
                </div>
                <div class="col-auto">
                  <a href="{{ $route_pengadaan_aktif }}"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Nominal Arsip Pengadaan --}}
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                    <a href="{{ $route_arsip_pengadaan }}" class="stretched-link text-reset text-decoration-none">Arsip Pengadaan</a>
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $nominal_arsip_pengadaan }}</div>
                </div>
                <div class="col-auto">
                  <a href="{{ $route_arsip_pengadaan }}"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endhasanyrole

    </div>
  @else
    <div class="alert alert-danger" role="alert">
      Akun Anda belum aktif. Silahkan hubungi Administrator dahulu.
    </div>
  @endhasanyrole
@endsection
