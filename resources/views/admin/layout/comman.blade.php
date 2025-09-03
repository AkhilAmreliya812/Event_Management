<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" href="http://localhost/event_manager/public//logo/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    
    <style>

        .main-content {
            margin-top: 80px;;
            margin-left: 200px;
        }

        .sidebar {
            height: 100vh;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #331b1b;
            /* Bootstrap info color */
            padding-top: 100px;
            /* space for navbar */
        }

        .sidebar a {
            margin-left: 15px;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-dark" href="{{ route('admin-dashbord') }}">
                <img src="http://localhost/event_manager/public/logo/logo.png" alt="logo/logo.png" height="50px" width="50px"> Advanture
            </a>
            <li class="nav-item dropdown list-unstyled justify-content-end me-md-5">
                <span class="dropdown-toggle me-md-5" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{  auth()->user()->name }}
                </span>
                <ul class="dropdown-menu dropdown-menu-dark bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin-changePassword') }}">Change Password</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin-logout') }}">Logout</a></li>
                  </ul>
              </li>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar bg-dark">
        <a href="{{ route('admin-dashbord') }}">Dashbord</a>
        <a href="{{ route('admin-events') }}">Events</a>
        <a href="{{ route('admin-contactLead') }}">Contact Lead</a>        
    </div>

    <div class="main-content">
        @yield('main')
    </div>

</body>

</html>
