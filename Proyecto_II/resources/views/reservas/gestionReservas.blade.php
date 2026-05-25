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

            <button type="button"
                    class="btn btn-outline-light"
                    onclick="cambiarIdiomaReservas('es')">
                ES
            </button>

            <button type="button"
                    class="btn btn-outline-light"
                    onclick="cambiarIdiomaReservas('en')">
                EN
            </button>

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
                                        onclick="cancelarReserva(this.dataset.url)"

                                        {{ $reserva->estado == 'CANCELADA' ? 'disabled' : '' }}>

                                    Cancelar
                                </button>

                                {{-- Eliminar --}}
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-url="{{ $reserva->idReserva }}"
                                        onclick="eliminarReserva(this.dataset.url)">

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

        document.addEventListener("DOMContentLoaded", cargarReservas);

        const textosReservas = {
            es: {
                empty: "No hay reservas",
                active: "ACTIVA",
                cancelled: "CANCELADA",
                finished: "FINALIZADA",
                cancel: "Cancelar",
                delete: "Eliminar",
                confirmDelete: "Seguro que deseas eliminar esta reserva?",
                confirmCancel: "Seguro que deseas cancelar esta reserva?",
                deleteFallback: "Error al eliminar reserva",
                cancelFallback: "Error al cancelar",
                loadError: "Error al cargar reservas",
                filterError: "Error al filtrar"
            },
            en: {
                empty: "No reservations found",
                active: "ACTIVE",
                cancelled: "CANCELLED",
                finished: "FINISHED",
                cancel: "Cancel",
                delete: "Delete",
                confirmDelete: "Are you sure you want to delete this reservation?",
                confirmCancel: "Are you sure you want to cancel this reservation?",
                deleteFallback: "Error deleting reservation",
                cancelFallback: "Error cancelling reservation",
                loadError: "Error loading reservations",
                filterError: "Error filtering reservations"
            }
        };

        function textoReservas(key) {
            return textosReservas[getLocale()]?.[key] || textosReservas.es[key];
        }

        function traducirEstado(estado) {
            if (estado === "ACTIVA") return textoReservas("active");
            if (estado === "CANCELADA") return textoReservas("cancelled");
            if (estado === "FINALIZADA") return textoReservas("finished");
            return estado;
        }

        function cambiarIdiomaReservas(locale) {
            setLocale(locale);
            cargarReservas();
        }

        function renderReservas(reservas) {

            let tbody =
                document.getElementById("tbodyReservas");

            tbody.innerHTML = "";

            if (reservas.length === 0) {

                tbody.innerHTML = `
                    <tr>
                        <td colspan="5">
                            ${textoReservas("empty")}
                        </td>
                    </tr>
                `;

                return;
            }

            reservas.forEach(r => {

                const idReserva = r.idReserva;
                const nombreUsuario = r.nombreUsuario ?? r.usuario?.nombre ?? "";
                const nombreClase = r.nombreClase ?? r.clase?.nombre ?? "";
                const fechaReserva = String(r.fechaReserva ?? "").split("T")[0];

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
                        <td>${nombreUsuario}</td>
                        <td>${nombreClase}</td>
                        <td>${fechaReserva}</td>
                        <td class="${color}">
                            ${traducirEstado(r.estado)}
                        </td>
                        <td>
                            <button type="button"
                                    class="btn btn-sm btn-warning"
                                    onclick="cancelarReserva(${idReserva})"
                                    ${disabled}>
                                ${textoReservas("cancel")}
                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="eliminarReserva(${idReserva})">
                                ${textoReservas("delete")}
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        function cargarReservas() {
            authFetch("/reservas")
                .then(res => res.json())
                .then(json => renderReservas(json.data || []))
                .catch(err => {
                    console.error(err);
                    alert(textoReservas("loadError"));
                });
        }

        function eliminarReserva(id) {

            if (!confirm(textoReservas("confirmDelete"))) {
                return;
            }

            authFetch(`/reservas/${id}`, {

                method: "DELETE"

            })

            .then(res => {

                if (!res.ok) {

                    return res.json().then(error => {
                        throw new Error(error.message || textoReservas("deleteFallback"));
                    });
                }

                return res.json();
            })

            .then(data => {
                alert(data?.message || textoReservas("delete"));
                cargarReservas();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

        function cancelarReserva(id) {

            if (!confirm(textoReservas("confirmCancel"))) {
                return;
            }

            authFetch(`/reservas/cancelar/${id}`, {

                method: "PUT"

            })

            .then(res => {

                if (!res.ok) {

                    return res.json().then(error => {
                        throw new Error(error.message || textoReservas("cancelFallback"));
                    });
                }

                return res.json();
            })

            .then(data => {
                alert(data?.message || textoReservas("cancel"));
                cargarReservas();
            })

            .catch(err => {

                console.error(err);

                alert(err.message);
            });
        }

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

                cargarReservas();

                return;
            }

            authFetch(`/reservas/estado/${estado}`)

                .then(res => res.json())

                .then(data => {

                    renderReservas(data.data || []);

                })

                .catch(err => {

                    console.error(err);

                    alert(textoReservas("filterError"));
                });
        }

    </script>

</body>

</html>
