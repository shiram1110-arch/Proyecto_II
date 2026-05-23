<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Horario de Clases</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet"
          href="{{ asset('css/horarioClases.css') }}">

</head>

<body>

    <div class="container mt-5">

        {{-- Navbar --}}
        <nav class="navbar header px-4">

            <div class="d-flex align-items-center">

                <img src="{{ asset('img/logo.png') }}"
                     class="logo me-2">

                <h4 class="titulo m-0">
                    Horarios de clases
                </h4>

            </div>

            <div class="d-flex">

                <a href="{{ url('/inicio') }}"
                   class="btn btn-login-neon">

                    Volver
                </a>

            </div>

        </nav>

        {{-- Título --}}
        <div class="text-center mb-2 subtitulo-dia">

            Escoge un día

        </div>

        {{-- Días --}}
        <div class="dias text-center mb-4">

            <a href="{{ url('/horarioClases?dia_semana=LUNES') }}"
               class="dia {{ $diaActual == 'LUNES' ? 'activo' : '' }}">

                LUN
            </a>

            <a href="{{ url('/horarioClases?dia_semana=MARTES') }}"
               class="dia {{ $diaActual == 'MARTES' ? 'activo' : '' }}">

                MAR
            </a>

            <a href="{{ url('/horarioClases?dia_semana=MIERCOLES') }}"
               class="dia {{ $diaActual == 'MIERCOLES' ? 'activo' : '' }}">

                MIÉ
            </a>

            <a href="{{ url('/horarioClases?dia_semana=JUEVES') }}"
               class="dia {{ $diaActual == 'JUEVES' ? 'activo' : '' }}">

                JUE
            </a>

            <a href="{{ url('/horarioClases?dia_semana=VIERNES') }}"
               class="dia {{ $diaActual == 'VIERNES' ? 'activo' : '' }}">

                VIE
            </a>

            <a href="{{ url('/horarioClases?dia_semana=SABADO') }}"
               class="dia {{ $diaActual == 'SABADO' ? 'activo' : '' }}">

                SÁB
            </a>

            <a href="{{ url('/horarioClases?dia_semana=DOMINGO') }}"
               class="dia {{ $diaActual == 'DOMINGO' ? 'activo' : '' }}">

                DOM
            </a>

        </div>

        {{-- Lista --}}
        <div class="lista-clases">

            @foreach ($clases as $clase)

                <div class="clase-item">

                    <div class="hora">

                        {{ \Carbon\Carbon::parse($clase->horario)->format('H:i') }}

                    </div>

                    <div class="info">

                        <div class="nombre">

                            {{ $clase->nombre }}

                        </div>

                    </div>

                    <a href="{{ url('/reservas/' . $clase->idClase) }}"
                       class="btn-mas">

                        Más
                    </a>

                </div>

            @endforeach

        </div>

        {{-- Sin clases --}}
        @if ($clases->isEmpty())

            <div class="text-center mt-4">

                <p>
                    No hay clases disponibles para este día
                </p>

            </div>

        @endif

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

    {{-- JS --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

        requireAuth();

    </script>

</body>

</html>