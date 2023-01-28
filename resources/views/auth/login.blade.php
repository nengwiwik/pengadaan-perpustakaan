<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="{{ asset('image/icon.png') }}" width="25px" height="25px">

  <title>Login | {{ env('APP_NAME') }}</title>

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
                    <h1 class="h4 text-gray-900 mb-4">Log in</h1>
                  </div>
                  <form class="user" method="POST" action="{{ route('login') }}">
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
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="exampleInputPassword"
                        placeholder="Password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      <i class="fas fa-sign-out "></i> Log in
                    </button>
                  </form>
                  <div class="text-center mt-3">
                    <p>OR</p>
                    <a name="login-undira" id="login-undira" class="btn btn-dark btn-block mt-3"
                      href="{{ route('socialite.redirect', 'google') }}" role="button"
                      style="font-size: .8rem;border-radius: 10rem;padding: .75rem 1rem; background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgY2xpcC1wYXRoPSJ1cmwoI2NsaXAwKSI+CjxwYXRoIGQ9Ik01LjI2NTk5IDkuNzY1QzUuNzMzODcgOC4zNDk0NSA2LjYzNjg1IDcuMTE3ODEgNy44NDYxMSA2LjI0NThDOS4wNTUzNiA1LjM3Mzc4IDEwLjUwOTEgNC45MDU5NCAxMiA0LjkwOUMxMy42OSA0LjkwOSAxNS4yMTggNS41MDkgMTYuNDE4IDYuNDkxTDE5LjkxIDNDMTcuNzgyIDEuMTQ1IDE1LjA1NSAwIDEyIDBDNy4yNjk5OSAwIDMuMTk3OTkgMi42OTggMS4yMzk5OSA2LjY1TDUuMjY1OTkgOS43NjVaIiBmaWxsPSIjRUE0MzM1Ii8+CjxwYXRoIGQ9Ik0xNi4wNDAxIDE4LjAxM0MxNC45NTAxIDE4LjcxNiAxMy41NjYxIDE5LjA5MSAxMi4wMDAxIDE5LjA5MUMxMC41MTUxIDE5LjA5NCA5LjA2Njg2IDE4LjYyOTkgNy44NjAzIDE3Ljc2NDRDNi42NTM3MyAxNi44OTg4IDUuNzUgMTUuNjc1NiA1LjI3NzA2IDE0LjI2OEwxLjIzNzA2IDE3LjMzNUMyLjIyODMxIDE5LjM0MTMgMy43NjIzMyAyMS4wMjk0IDUuNjY0ODggMjIuMjA3NUM3LjU2NzQ0IDIzLjM4NTcgOS43NjIyNyAyNC4wMDY3IDEyLjAwMDEgMjRDMTQuOTMzMSAyNCAxNy43MzUxIDIyLjk1NyAxOS44MzQxIDIxTDE2LjA0MTEgMTguMDEzSDE2LjA0MDFaIiBmaWxsPSIjMzRBODUzIi8+CjxwYXRoIGQ9Ik0xOS44MzQgMjFDMjIuMDI5IDE4Ljk1MiAyMy40NTQgMTUuOTA0IDIzLjQ1NCAxMkMyMy40NTQgMTEuMjkgMjMuMzQ1IDEwLjUyNyAyMy4xODIgOS44MThIMTJWMTQuNDU1SDE4LjQzNkMxOC4xMTkgMTYuMDE0IDE3LjI2NiAxNy4yMjEgMTYuMDQxIDE4LjAxM0wxOS44MzQgMjFaIiBmaWxsPSIjNEE5MEUyIi8+CjxwYXRoIGQ9Ik01LjI3Njk5IDE0LjI2OEM1LjAzMjM1IDEzLjUzNjkgNC45MDgwNiAxMi43NzA5IDQuOTA4OTkgMTJDNC45MDg5OSAxMS4yMTggNS4wMzM5OSAxMC40NjcgNS4yNjU5OSA5Ljc2NUwxLjIzOTk5IDYuNjVDMC40MTYzNzIgOC4zMTI2OSAtMC4wMDgyMDIwMSAxMC4xNDQ1IC01LjYxNTc3ZS0wNiAxMkMtNS42MTU3N2UtMDYgMTMuOTIgMC40NDQ5OTQgMTUuNzMgMS4yMzY5OSAxNy4zMzVMNS4yNzY5OSAxNC4yNjhaIiBmaWxsPSIjRkJCQzA1Ii8+CjwvZz4KPGRlZnM+CjxjbGlwUGF0aCBpZD0iY2xpcDAiPgo8cmVjdCB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIGZpbGw9IndoaXRlIi8+CjwvY2xpcFBhdGg+CjwvZGVmcz4KPC9zdmc+Cg==); background-repeat:no-repeat; background-position:12px center">Log
                      in with Google</a>
                    <hr />
                  </div>
                  <div class="text-center">
                    <a class="small" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                    <br />
                    <a class="small" href="{{ route('register') }}">
                      Create an account
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
