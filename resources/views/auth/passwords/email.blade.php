<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="{{ asset('image/icon.png') }}" width="25px" height="25px">

  <title>Reset Password | {{ env('APP_NAME') }}</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css') }}">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<img src="" alt="">

<body class=""
  style="background:url('{{ asset('image/undira.jpeg') }}') no-repeat center center fixed; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;">
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="{{ asset('image/logo_baru.png') }}" alt="UNDIRA">
                    <h1 class="h4 text-gray-900 mb-4">{{ __('Reset Password') }}</h1>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                  </div>
                  <form class="user" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                        id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Email" name="email"
                        value="{{ old('email') }}" autofocus autocomplete="off">
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      <i class="fas fa-sign-out "></i> {{ __('Send Password Reset Link') }}
                    </button>
                  </form>

                  <div class="text-center mt-3">
                    <a class="small" href="{{ route('login') }}">
                      Remember your account?
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
