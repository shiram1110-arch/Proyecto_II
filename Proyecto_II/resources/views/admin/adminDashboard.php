<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Laravel --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="header d-flex justify-content-between align-items-center px-4 py-2">
        <h4 class="titulo m-0">Panel Administrador</h4>

        {{-- Ruta Laravel --}}
        <a href="{{ url('/inicio') }}" class="btn btn-login-neon">
            Volver
        </a>
    </div>

    <div class="container mt-5">

        <div class="text-center mb-4">
            {{-- Imagen Laravel --}}
            <img src="{{ asset('img/logo.png') }}" class="logo" alt="Logo">

            <p class="subtitulo">
                Gestión del sistema VIKINGS
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            {{-- Gestionar Usuarios --}}
            <div class="col-md-4">
                <div class="registro-card p-4 text-center card-hover"
                    data-url="{{ url('/usuariosVista') }}"
                    onclick="location.href=this.dataset.url">

                    <h5 class="titulo">Gestionar Usuarios</h5>

                    <p class="subtitulo">
                        Agregar, eliminar y actualizar usuarios
                    </p>
                </div>
            </div>

            {{-- Gestionar Clases --}}
            <div class="col-md-4">
                <div class="registro-card p-4 text-center card-hover"
                    data-url="{{ url('/clasesVista') }}"
                    onclick="location.href=this.dataset.url">

                    <h5 class="titulo">Gestionar Clases</h5>

                    <p class="subtitulo">
                        Agregar, eliminar y actualizar clases
                    </p>
                </div>
            </div>

            {{-- Gestionar Reservas --}}
            <div class="col-md-4">
                <div class="registro-card p-4 text-center card-hover"
                    data-url="{{ url('/gestionReservas') }}"
                    onclick="location.href=this.dataset.url">

                    <h5 class=" titulo">Gestionar Reservas</h5>

                    <p class="subtitulo">
                        Agregar, eliminar y actualizar reservas
                    </p>
                </div>
            </div>

        </div>

    </div>

    {{-- JS Laravel --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        requireAdmin();
    </script>

</body>

</html>