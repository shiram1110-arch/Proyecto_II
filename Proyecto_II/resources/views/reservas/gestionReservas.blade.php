<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gestión de Reservas</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet"
          href="{{ asset('css/usuarios.css') }}">

    <style>

        .filtro-activo {

            background-color: white !important;

            color: black !important;
        }

    </style>

</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar header px-4">

        <div class="d-flex align-items-center">

            <img src="{{ asset('img/logo.png') }}"
                 class="logo me-2">

            <h4 class="titulo m-0">
                Administración de Reservas
            </h4>

        </div>

        <div class="d-flex gap-2">

            <a href="{{ url('/admin/adminDashboard') }}"
               class="btn btn-login-neon">
                Volver
            </a>

        </div>

    </nav>

    {{-- Contenido --}}
    <div class="container mt-5">

        <div class="text-center mb-4">

            <h2 class="titulo">
                Lista de Reservas
            </h2>

            <p class="subtitulo">
                Visualiza, cancela o elimina reservas
            </p>

        </div>

        {{-- Filtros --}}
        <div class="mb-4 d-flex gap-2 justify-content-center flex-wrap">

            <button class="btn btn-outline-light filtro-btn"
                    onclick="filtrar('', this)">

                Todas
            </button>

            <button class="btn btn-outline-success filtro-btn"
                    onclick="filtrar('ACTIVA', this)">

                Activas
            </button>

            <button class="btn btn-outline-warning filtro-btn"
                    onclick="filtrar('CANCELADA', this)">

                Canceladas
            </button>

            <button class="btn btn-outline-info filtro-btn"
                    onclick="filtrar('FINALIZADA', this)">

                Finalizadas
            </button>

        </div>

        {{-- Tabla --}}
        <div class="registro-card p-4">

            <table class="table table-dark table-hover text-center align-middle">

                <thead>

                    <tr>

                        <th>Usuario</th>

                        <th>Clase</th>

                        <th>Fecha</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody id="tbodyReservas">

                    @foreach ($reservas as $reserva)

                        <tr>

                            <td>
                                {{ $reserva->usuario->nombre }}
                            </td>

                            <td>
                                {{ $reserva->clase->nombre }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($reserva->fechaReserva)->format('Y-m-d') }}
                            </td>

                            <td>

                                <span class="
                                    {{ $reserva->estado == 'ACTIVA' ? 'text-success' : '' }}
                                    {{ $reserva->estado == 'CANCELADA' ? 'text-warning' : '' }}
                                    {{ $reserva->estado == 'FINALIZADA' ? 'text-info' : '' }}
                                ">

                                    {{ $reserva->estado }}

                                </span>

                            </td>

                            <td>

                                {{-- Cancelar --}}
                                <button type="button"
                                        class="btn btn-sm btn-warning"
                                        data-url="{{ $reserva->idReserva }}"
                                        onclick="cancelarReserva(this=dataset.url)"

                                        {{ $reserva->estado == 'CANCELADA' ? 'disabled' : '' }}>

                                    Cancelar
                                </button>

                                {{-- Eliminar --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-url="{{ $reserva->idReserva }}"
                                        onclick="eliminarReserva(this=dataset.url)">

                                    Eliminar
                                </button>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- JS --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

        requireAdmin();

        // Eliminar
        function eliminarReserva(id) {

            if (!confirm("¿Seguro que deseas eliminar esta reserva?")) {
                return;
            }

            fetch(`/api/reservas/${id}`, {

                method: "DELETE",

                headers: {

                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content")
                }

            })

            .then(res => {

                if (!res.ok) {

                    throw new Error(
                        "Error al eliminar reserva"
                    );
                }

                alert("🗑️ Reserva eliminada");

                location.reload();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

        // Cancelar
        function cancelarReserva(id) {

            if (!confirm("¿Seguro que deseas cancelar esta reserva?")) {
                return;
            }

            fetch(`/api/reservas/cancelar/${id}`, {

                method: "PUT",

                headers: {

                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content")
                }

            })

            .then(res => {

                if (!res.ok) {

                    throw new Error(
                        "Error al cancelar"
                    );
                }

                alert("⚠️ Reserva cancelada");

                location.reload();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

        // Filtrar
        function filtrar(estado, btn) {

            document
                .querySelectorAll(".filtro-btn")
                .forEach(b => {

                    b.classList.remove("filtro-activo");
                });

            if (btn) {

                btn.classList.add("filtro-activo");
            }

            if (estado === "") {

                location.reload();

                return;
            }

            fetch(`/api/reservas/estado/${estado}`)

                .then(res => res.json())

                .then(data => {

                    let tbody =
                        document.getElementById("tbodyReservas");

                    tbody.innerHTML = "";

                    if (data.length === 0) {

                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5">
                                    No hay reservas
                                </td>
                            </tr>
                        `;

                        return;
                    }

                    data.forEach(r => {

                        let color =
                            r.estado === "ACTIVA"
                            ? "text-success"
                            : r.estado === "CANCELADA"
                            ? "text-warning"
                            : "text-info";

                        let disabled =
                            r.estado === "CANCELADA"
                            ? "disabled"
                            : "";

                        tbody.innerHTML += `

                            <tr>

                                <td>${r.nombreUsuario}</td>

                                <td>${r.nombreClase}</td>

                                <td>${r.fechaReserva}</td>

                                <td class="${color}">
                                    ${r.estado}
                                </td>

                                <td>

                                    <button type="button"
                                            class="btn btn-sm btn-warning"

                                            onclick="cancelarReserva(${r.idReserva})"

                                            ${disabled}>

                                        Cancelar
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-danger"

                                            onclick="eliminarReserva(${r.idReserva})">

                                        Eliminar
                                    </button>

                                </td>

                            </tr>
                        `;
                    });

                })

                .catch(err => {

                    console.error(err);

                    alert("Error al filtrar");
                });
        }

    </script>

</body>

</html>