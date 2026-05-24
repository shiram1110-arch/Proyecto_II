<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inicio</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
</head>

<body>

    <header class="header shadow">

        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" class="logo-header">
                <span class="navbar-brand m-0" id="nombreUsuario"></span>
            </div>

            <div id="authButtons">
                <a href="/login" class="btn btn-login-neon">Login</a>
            </div>

        </div>

    </header>

    <!-- CAROUSEL -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

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

    </div>

    <!-- CLASES -->
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

    </section>

    <!-- FOOTER -->
    <footer class="footer mt-5 py-4 text-center">

        <div class="container">

            <img src="{{ asset('img/logo.png') }}" width="120" class="mb-2">

            <p class="mb-1">© 2026 VIKINGS</p>
            <p class="mb-0 small">Todos los derechos reservados</p>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {

            const container =
                document.getElementById("authButtons");



            if (isAuthenticated()) {

                if (await isAdmin()) {

                    container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">

                    <li class="nav-item">
                        <a class="nav-link active"
                           href="/clases/horarioClases">
                            Clases
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="/admin/adminDashboard">
                            Admin
                        </a>
                    </li>

                    <li class="nav-item">
                        <button
                            class="nav-link btn-logout"
                            onclick="logout()">

                            Salir

                        </button>
                    </li>

                </ul>
            `;
                } else if (await isUser()) {

                    container.innerHTML = `
                <ul class="nav nav-tabs custom-nav">

                    <li class="nav-item">
                        <a class="nav-link"
                           href="/reservas/historial">
                            Historial
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active"
                           href="/clases/horarioClases">
                            Clases
                        </a>
                    </li>

                    <li class="nav-item">
                        <button
                            class="nav-link btn-logout"
                            onclick="logout()">

                            Salir

                        </button>
                    </li>

                </ul>
            `;
                }
            }



            await getNombre();
        });
    </script>

</body>

</html>