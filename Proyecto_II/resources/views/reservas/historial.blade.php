<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Historial de Clases</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/historial.css') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark">

        <div class="container-fluid">

            <a class="navbar-brand" href="#">

                {{ $usuario->nombre ?? 'Usuario' }}

            </a>

            <div class="d-flex">

                <a href="{{ url('/inicio') }}"
                   class="btn btn-login-neon">

                    Volver
                </a>

            </div>

        </div>

    </nav>

    {{-- Contenido --}}
    <div class="container mt-5">

        <div class="card p-4">

            <h3 class="mb-4 text-center">

                Historial de Clases Reservadas

            </h3>

            <table class="table table-striped table-dark text-center">

                <thead>

                    <tr>

                        <th>Clase</th>

                        <th>Capacidad de personas</th>

                        <th>Fecha</th>

                        <th>Hora</th>

                        <th>Estado</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse ($reservas as $reserva)

                        <tr>

                            <td>
                                {{ $reserva->clase->nombre }}
                            </td>

                            <td>
                                {{ $reserva->clase->capacidad }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('Y-m-d') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reserva->clase->horario)->format('H:i') }}
                            </td>

                            <td>
                                {{ $reserva->estado }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                No hay reservas registradas

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Footer --}}
    <footer class="footer mt-5 py-4 text-center">

        <div class="container">

            <img src="{{ asset('img/logo.png') }}"
                 alt="Logo"
                 width="120"
                 class="mb-2">

            <p class="mb-1">
                © 2026 VIKINGS
            </p>

            <p class="mb-0 small">
                Todos los derechos reservados
            </p>

        </div>

    </footer>

</body>

</html>