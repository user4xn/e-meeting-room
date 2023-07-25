
<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>Not Authorized - E-Arsip Digital</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/e-ArsipDigital-Ori.svg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>

    <div class="container-xxl container-p-y">
      <div class="misc-wrapper" style="margin-top: -70px;">
        <h2 class="mb-1 mx-2">You are not authorized!</h2>
        <p class="mb-4 mx-2">
          Seseorang terdeteksi sedang login menggunakan akun ini di perangkat lain, silahkan menunggu beberapa saat atau login kembali untuk memulai sesi yang baru.
          Hubungi Administrator jika aktivitas ini mencurigakan!
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary mb-4">Kembali Login</a>
        <div class="mt-4">
          <img
            src="{{ asset('assets/img/illustrations/page-misc-you-are-not-authorized.png')}}"
            alt="page-misc-not-authorized"
            width="170"
            class="img-fluid"
          />
        </div>
      </div>
    </div>
    <div class="container-fluid misc-bg-wrapper">
      <img
        src="{{ asset('assets/img/illustrations/bg-shape-image-light.png')}}"
        alt="page-misc-not-authorized"
        data-app-light-img="illustrations/bg-shape-image-light.png"
        data-app-dark-img="illustrations/bg-shape-image-dark.png"
      />
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js')}}"></script>
    <script src="{{ asset('assets/js/main.js')}}"></script>

  </body>
</html>
