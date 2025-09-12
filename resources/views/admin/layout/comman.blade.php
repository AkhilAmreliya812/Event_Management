<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    {{-- datatable css cdn --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.dataTables.min.css">

    <link rel="icon" href="http://localhost/event_manager/public//logo/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    


    <style>
        .hover-bg:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu[data-bs-popper] {
            top: 100%;
            left: -100px;
            margin-top: .125rem;
        }

        .dropdown-toggle::after {
            display: none;
           
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-dark fw-bold" href="{{ route('admin-dashbord') }}">
                <img src="http://localhost/event_manager/public/logo/logo.png" alt="logo/logo.png" height="40"
                    width="40" class="me-2">
                Adventure
            </a>

            <li class="nav-item dropdown list-unstyled me-4">
                <span class="dropdown-toggle fw-bold" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="http://localhost/event_manager/public/{{ auth()->user()->profile_photo }}" alt=""
                        height="30" width="30">
                </span>
                <ul class="dropdown-menu dropdown-menu-dark bg-dark" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin-changePassword') }}">Change Password</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin-logout') }}">Logout</a></li>
                </ul>
            </li>
        </div>
    </nav>

    <!-- Layout -->
    <div class="d-flex" style="margin-top: 56px;"> <!-- 56px = navbar height -->
        <!-- Sidebar -->
        <!-- Sidebar -->
        <div class="bg-dark text-light p-3 sidebar" style="width: 220px; min-height: calc(100vh - 56px);">
            <h5 class="text-center border-bottom pb-2">Menu</h5>

            <a href="{{ route('admin-dashbord') }}"
                class="d-block py-2 px-2 rounded text-decoration-none 
              {{ request()->routeIs('admin-dashbord') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}">
                Dashboard
            </a>

            <a href="{{ route('admin-events') }}"
                class="d-block py-2 px-2 rounded text-decoration-none 
              {{ request()->routeIs('admin-events') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}
               {{ request()->routeIs('admin-add_event') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}
                {{ request()->routeIs('admin-eventList') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}
                 {{ request()->routeIs('admin-editEvent') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}">
                Events
            </a>

            <a href="{{ route('admin-contactLead') }}"
                class="d-block py-2 px-2 rounded text-decoration-none 
              {{ request()->routeIs('admin-contactLead') ? 'bg-secondary text-dark fw-bold' : 'text-light hover-bg' }}">
                Contact Lead
            </a>
        </div>


        <!-- Main Content -->
        <div class="flex-grow-1 p-4 bg-light main-content">
            @yield('main')
        </div>
    </div>
</body>


</html>
