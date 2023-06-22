@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center mt-3">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ __('Password') }}</div>

          <div class="card-body">

            @if (session('status_password'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status_password') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @if (session('status_password_error'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('status_password_error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="{{ route('profil.update-password') }}" enctype="multipart/form-data">
                  @method('PATCH')
                  @csrf

                  <div class="row mb-3">
                    <label for="current_password"
                      class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>

                    <div class="col-md-6">
                      <input id="current_password" type="password"
                        class="form-control @error('current_password') is-invalid @enderror" name="current_password"
                        value="{{ old('current_password') }}" required autocomplete="current_password">

                      @error('current_password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="password"
                      class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>

                    <div class="col-md-6">
                      <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password"
                        value="{{ old('password') }}" required autocomplete="off">

                      @error('password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="password_confirmation"
                      class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                    <div class="col-md-6">
                      <input id="password_confirmation" type="password"
                        class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                        value="{{ old('password_confirmation') }}" required autocomplete="off">

                      @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                        {{ __('Change password') }}
                      </button>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
