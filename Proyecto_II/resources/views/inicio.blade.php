<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
</head>

<body>

    <header class="header shadow">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('img/logo.png') }}"  class="logo-header">
                <a class="navbar-brand m-0" href="#">
                    <span id="nombreUsuario"></span>
                </a>
            </div>

            <div id="authButtons">
                <a href="/login" class="btn btn-login-neon">Login</a>
            </div>


        </div>
    </header>

    <div id="carouselExample" class="carousel slide">

        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="{{ asset('img/crossfit.png') }}" class="d-block w-100">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/pilates.png') }}" class="d-block w-100">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/yoga.png') }}" class="d-block w-100">
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

    <section class="container mt-5">
        <h2 class="text-center mb-4 titulo">Nuestras Clases</h2>

        <div class="row text-center">

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/spinning.png') }}" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/zumba.png') }}" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/funcional.png') }}" class="img-fluid">
                </div>
            </div>

        </div>

        <footer class="footer mt-5 py-4 text-center">
            <div class="container">
                <img src="/img/logo.png" width="120" class="mb-2">
                <p class="mb-1">© 2026 VIKINGS</p>
                <p class="mb-0 small">Todos los derechos reservados</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script src="{{ asset('js/auth.js') }}"></script>

        <script>
            const container = document.getElementById("authButtons");

            if (isAuthenticated()) {

                if (isAdmin()) {
                    container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/adminDashboard">Admin</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" onclick="logout()">Salir</button>
                    </li>
                </ul>
                `;
                }

                else if (isUser()) {
                    container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/historial">Historial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/horarioClases">Clases</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link btn-logout" onclick="logout()">Salir</button>
                    </li>
                </ul>
                `;
                }
            }
        </script>

        <script>getNombre()</script>

</body>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Laravel --}}
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
</head>

<body>

    <header class="header shadow">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" class="logo-header">

                <a class="navbar-brand m-0" href="#">
                    {{-- Nombre del usuario autenticado --}}
                    @auth
                        {{ Auth::user()->name }}
                    @else
                        Invitado
                    @endauth
                </a>
            </div>

            <div id="authButtons">

                {{-- Usuario NO autenticado --}}
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login-neon">
                        Login
                    </a>
                @endguest

                {{-- Usuario autenticado --}}
                @auth

                    {{-- ADMIN --}}
                    @if(Auth::user()->role === 'admin')

                        <ul class="nav nav-tabs custom-nav">

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('/horarioClases') }}">
                                    Clases
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/adminDashboard') }}">
                                    Admin
                                </a>
                            </li>

                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="nav-link btn-logout border-0 bg-transparent">
                                        Salir
                                    </button>
                                </form>
                            </li>

                        </ul>

                    {{-- USER --}}
                    @elseif(Auth::user()->role === 'user')

                        <ul class="nav nav-tabs custom-nav">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/historial') }}">
                                    Historial
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('/horarioClases') }}">
                                    Clases
                                </a>
                            </li>

                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="nav-link btn-logout border-0 bg-transparent">
                                        Salir
                                    </button>
                                </form>
                            </li>

                        </ul>

                    @endif

                @endauth

            </div>

        </div>
    </header>

    {{-- Carousel --}}
    <div id="carouselExample" class="carousel slide">

        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="{{ asset('img/crossfit.png') }}" class="d-block w-100">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/pilates.png') }}" class="d-block w-100">
            </div>

            <div class="carousel-item">
                <img src="{{ asset('img/yoga.png') }}" class="d-block w-100">
            </div>

        </div>

        <button class="carousel-control-prev" type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <button class="carousel-control-next" type="button"
            data-bs-target="#carouselExample"
            data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

    {{-- Clases --}}
    <section class="container mt-5">

        <h2 class="text-center mb-4 titulo">
            Nuestras Clases
        </h2>

        <div class="row text-center">

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/spinning.png') }}" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/zumba.png') }}" class="img-fluid">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card card-custom">
                    <img src="{{ asset('img/funcional.png') }}" class="img-fluid">
                </div>
            </div>

        </div>

    </section>

    {{-- Footer --}}
    <footer class="footer mt-5 py-4 text-center">

        <div class="container">

            <img src="{{ asset('img/logo.png') }}" width="120" class="mb-2">

            <p class="mb-1">
                © 2026 VIKINGS
            </p>

            <p class="mb-0 small">
                Todos los derechos reservados
            </p>

        </div>

    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
</html>