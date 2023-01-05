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
  @else
    <div class="alert alert-danger" role="alert">
      Akun Anda belum aktif. Silahkan hubungi Administrator dahulu.
    </div>
  @endhasanyrole
@endsection
