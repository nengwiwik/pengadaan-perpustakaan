<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="{{ asset('image/icon.png') }}" width="25px" height="25px">

  <title>Register | {{ env('APP_NAME') }}</title>

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
                    <h1 class="h4 text-gray-900 mb-4">Register</h1>
                  </div>
                  <div class="text-center">
                    <p>Please contact us below</p>
                    <p class="text-primary">perpustakaan@undira.ac.id</p>
                    <p class="text-primary">+62 123 456 789</p>
                    <p class="text-primary">Jl. Tanjung Duren Bar. 2 No.1, RT 001/RW 005, Tanjung Duren Utara, Kec. Grogol petamburan, Kota Jakarta Barat, Daerah Khusus Ibukota Jakarta 11470</p>
                  </div>
                  <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-dark btn-user btn-block mt-3"
                      href="{{ route('socialite.redirect', 'google') }}" role="button">
                      Log in
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
