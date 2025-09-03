<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
  </script>
  <title>@yield('title')</title>
  <link rel="icon" type="image/x-icon" href="http://localhost/event_manager/public/logo/logo.png">

  <style>
    body {
      margin: 0;
      padding: 0;
    }
   
    footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      z-index: 1030;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <div class="container-fluid">

      <a class="navbar-brand text-dark" href="{{ route('home') }}">
        <img src="http://localhost/event_manager/public/logo/logo.png" alt="logo/logo.png" height="50px" width="50px"> Advanture
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
        <ul class="navbar-nav mx-3">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('about') }}">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('contact') }}">Contact Us</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

 

  <!-- Main Content -->
  <div class="content">
    @yield('main')
  </div>

  <!-- Footer -->
  <footer class="bg-info text-dark pt-4 pb-2">
    <div class="container">
      <div class="row gy-4">
        <div class="col-md-4">
          <h5 class="mb-3">Address</h5>
          <p class="mb-0">1320, Stratum @ Venus <br> Grounds, Nehru Nagar, <br>
            Satellite, Ahmedabad, <br> Gujarat 380015</p>
        </div>

        <div class="col-md-4">
          <h5 class="mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="{{ route('home') }}" class="text-dark text-decoration-none">Home</a></li>
            <li><a href="{{ route('about') }}" class="text-dark text-decoration-none">About Us</a></li>
            <li><a href="{{ route('contact') }}" class="text-dark text-decoration-none">Contact Us</a></li>
          </ul>
        </div>

        <div class="col-md-4">
          <h5 class="mb-3">Contact</h5>
          <p class="mb-1">📧 <a href="mailto:info@gmail.com" class="text-dark text-decoration-none">info@gmail.com</a></p>
          <p class="mb-0">📞 +91 11111 11111</p>
        </div>
      </div>
      <hr class="border-secondary my-3">
      <div class="text-center">
        <p class="mb-0">&copy; 2025 Your Company | All Rights Reserved</p>
      </div>
    </div>
  </footer>

</body>

</html>
