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

                <span id="nombreUsuarioHistorial">{{ $usuario->nombre ?? 'Usuario' }}</span>

            </a>

            <div class="d-flex">

                <button type="button"
                    class="btn btn-outline-light me-2"
                    onclick="cambiarIdiomaHistorial('es')">
                    ES
                </button>

                <button type="button"
                    class="btn btn-outline-light me-2"
                    onclick="cambiarIdiomaHistorial('en')">
                    EN
                </button>

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

                <tbody id="tbodyHistorial">

                    @forelse ($reservas->sortByDesc('fechaReserva') as $reserva)

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

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        requireAuth();

        const usuario = getUser();
        const nombreUsuario = document.getElementById("nombreUsuarioHistorial");

        if (usuario && nombreUsuario) {
            nombreUsuario.textContent = usuario.nombre || "Usuario";
        }

        const textosHistorial = {
            es: {
                empty: "No hay reservas registradas",
                active: "ACTIVA",
                cancelled: "CANCELADA",
                finished: "FINALIZADA",
                loadError: "No se pudo cargar el historial"
            },
            en: {
                empty: "No reservations found",
                active: "ACTIVE",
                cancelled: "CANCELLED",
                finished: "FINISHED",
                loadError: "The reservation history could not be loaded"
            }
        };

        function textoHistorial(key) {
            return textosHistorial[getLocale()]?.[key] || textosHistorial.es[key];
        }

        function traducirEstadoHistorial(estado) {
            if (estado === "ACTIVA") return textoHistorial("active");
            if (estado === "CANCELADA") return textoHistorial("cancelled");
            if (estado === "FINALIZADA") return textoHistorial("finished");
            return estado;
        }

        function cambiarIdiomaHistorial(locale) {
            setLocale(locale);
            cargarHistorial();
        }

        function formatDate(value) {
            if (!value) return "";

            return String(value).split("T")[0];
        }

        function formatTime(value) {
            if (!value) return "";

            const text = String(value);
            return text.includes("T")
                ? text.substring(11, 16)
                : text.substring(0, 5);
        }

        function renderHistorial(reservas) {
            const tbody = document.getElementById("tbodyHistorial");
            tbody.innerHTML = "";

            if (!reservas.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">${textoHistorial("empty")}</td>
                    </tr>
                `;
                return;
            }

            reservas
                .sort((a, b) => String(b.fechaReserva).localeCompare(String(a.fechaReserva)))
                .forEach(reserva => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${reserva.clase?.nombre ?? ""}</td>
                            <td>${reserva.clase?.capacidad ?? ""}</td>
                            <td>${formatDate(reserva.fechaReserva)}</td>
                            <td>${formatTime(reserva.clase?.horario)}</td>
                            <td>${traducirEstadoHistorial(reserva.estado)}</td>
                        </tr>
                    `;
                });
        }

        function cargarHistorial() {
            authFetch("/reservas/mis-clases")
            .then(res => res.json())
            .then(json => renderHistorial(json.data || []))
            .catch(error => {
                console.error(error);
                alert(textoHistorial("loadError"));
            });
        }

        cargarHistorial();
    </script>

</body>

</html>
