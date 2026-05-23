<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Laravel --}}
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
                data-url="{{ url('/crearClase') }}"
                onclick="window.location.href=this.dataset.url">

                + Crear Clase
            </button>

            <a href="{{ url('/adminDashboard') }}"
                class="btn btn-login-neon">

                Volver
            </a>

        </div>

    </nav>

    {{-- Contenido --}}
    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2 class="titulo">Lista de Clases</h2>

            <p class="subtitulo">
                Administra las clases del sistema
            </p>
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

            <table class="table table-dark table-hover text-center align-middle"
                id="tablaClases">

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

                <tbody id="tbodyClases">

                    @foreach ($clases as $clase)

                    <tr>

                        <td>{{ $clase->nombre }}</td>

                        <td>{{ $clase->descripcion }}</td>

                        <td>{{ $clase->diaSemana }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($clase->horario)->format('H:i') }}
                        </td>

                        <td>{{ $clase->capacidad }}</td>

                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- Editar --}}
                                <a href="{{ url('/clases/editar/' . $clase->idClase) }}"
                                    class="btn btn-sm btn-primary">

                                    Editar
                                </a>

                                {{-- Eliminar --}}
                                <button class="btn btn-sm btn-danger"
                                    data-url="{{ $clase->idClase }}"
                                    onclick="eliminarClase(this.dataset.url)">

                                    Eliminar
                                </button>

                            </div>

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

        // Eliminar clase
        function eliminarClase(id) {

            if (!confirm("¿Seguro que deseas eliminar esta clase?")) return;

            authFetch(`/clases/${id}`, {
                    method: "DELETE"
                })
                .then(res => {

                    if (!res.ok) {
                        throw new Error("Error al eliminar");
                    }

                    alert("✅ Clase eliminada");

                    window.location.reload();

                })
                .catch(err => alert(err.message));
        }

        // Buscador
        document.getElementById("buscador")
            .addEventListener("keyup", function() {

                let texto = this.value.trim();

                if (texto === "") {
                    window.location.reload();
                    return;
                }

                fetch(`/clases/buscar/${texto}`)
                    .then(res => res.json())
                    .then(data => {

                        let tbody = document.getElementById("tbodyClases");

                        tbody.innerHTML = "";

                        data.forEach(clase => {

                            let horaFormateada = clase.horario ?
                                clase.horario.substring(0, 5) :
                                "";

                            tbody.innerHTML += `
                                <tr>

                                    <td>${clase.nombre}</td>

                                    <td>${clase.descripcion}</td>

                                    <td>${clase.diaSemana}</td>

                                    <td>${horaFormateada}</td>

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

                    })
                    .catch(err => console.error("Error en búsqueda:", err));

            });
    </script>

</body>

</html>