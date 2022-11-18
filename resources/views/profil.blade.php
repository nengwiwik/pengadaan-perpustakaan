@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ __('Profile') }}</div>

          <div class="card-body">

            @if (session('status'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <div class="row">
              <div class="col-md-12">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                  @method('PATCH')
                  @csrf

                  <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                    <div class="col-md-6">
                      <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ $user->name }}" required autocomplete="name">

                      @error('name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                    <div class="col-md-6">
                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  {{-- / --}}

                  {{-- <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                  {{-- <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div> --}}

                  {{-- <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Change Profile Photo') }}</label>

                                    <div class="col-md-6">
                                        <input id="photo" type="file" class="form-control" name="photo">
                                    </div>
                                </div> --}}

                  <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                      <button type="submit" class="btn btn-primary">
                        {{ __('Update profile') }}
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
