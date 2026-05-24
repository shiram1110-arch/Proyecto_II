<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar header px-4">

        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" class="logo me-2" alt="Logo">

            <h4 class="titulo m-0">
                Administración de Clases
            </h4>
        </div>

        <div class="d-flex gap-2">

            <button class="btn btn-main"
                onclick="window.location.href='/clases/crear'">

                + Crear Clase
            </button>

            <a href="/admin/adminDashboard"
                class="btn btn-login-neon">

                Volver
            </a>

        </div>

    </nav>

    {{-- Contenido --}}
    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2 class="titulo">Lista de Clases</h2>
            <p class="subtitulo">Administra las clases del sistema</p>
        </div>

        {{-- Buscador --}}
        <div class="mb-4">
            <input type="text"
                id="buscador"
                class="form-control"
                placeholder="Buscar clase...">
        </div>

        {{-- Tabla --}}
        <div class="registro-card p-4">

            <table class="table table-dark table-hover text-center align-middle">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Día</th>
                        <th>Horario</th>
                        <th>Capacidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody id="tbodyClases"></tbody>

            </table>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        requireAdmin();

        let todasClases = [];

        document.addEventListener("DOMContentLoaded", () => {
            cargarClases();
        });

        // =========================
        // CARGAR CLASES (API)
        // =========================
        async function cargarClases() {

            try {
                const res = await fetch('/api/clases', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const json = await res.json();

                todasClases = json.data;

                render(todasClases);

            } catch (err) {
                console.error("Error cargando clases:", err);
            }
        }

        // =========================
        // RENDER TABLA
        // =========================
        function render(data) {

            const tbody = document.getElementById("tbodyClases");
            tbody.innerHTML = "";

            data.forEach(clase => {

                tbody.innerHTML += `
                    <tr>
                        <td>${clase.nombre}</td>
                        <td>${clase.descripcion ?? ''}</td>
                        <td>${clase.diaSemana}</td>
                        <td>${clase.horario.substring(0,5)}</td>
                        <td>${clase.capacidad}</td>

                        <td>
                            <div class="d-flex justify-content-center gap-2">

                                <a href="/clases/editar/${clase.idClase}"
                                   class="btn btn-sm btn-primary">
                                   Editar
                                </a>

                                <button class="btn btn-sm btn-danger"
                                        onclick="eliminarClase(${clase.idClase})">
                                   Eliminar
                                </button>

                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        // =========================
        // ELIMINAR (API)
        // =========================
        async function eliminarClase(id) {

            if (!confirm("¿Seguro que deseas eliminar esta clase?")) return;

            try {
                const res = await fetch(`/api/clases/${id}`, {
                    method: "DELETE",
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error("Error al eliminar");

                alert("✅ Clase eliminada");

                cargarClases();

            } catch (err) {
                alert(err.message);
            }
        }

        // =========================
        // BUSCADOR (FRONTEND)
        // =========================
        document.getElementById("buscador").addEventListener("keyup", (e) => {

            const texto = e.target.value.toLowerCase();

            const filtradas = todasClases.filter(c =>
                c.nombre.toLowerCase().includes(texto) ||
                (c.descripcion ?? '').toLowerCase().includes(texto)
            );

            render(filtradas);
        });

    </script>

</body>

</html>