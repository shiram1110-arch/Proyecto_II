<!DOCTYPE html>

<html xmlns:th="http://www.thymeleaf.org" lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Clases</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">


</head>

<body>


    <nav class="navbar header px-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" class="logo me-2">
            <h4 class="titulo m-0">Administración de Clases</h4>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-main" onclick="window.location.href='/crearClase'">
                + Crear Clase
            </button>
            <a href="/adminDashboard" class="btn btn-login-neon">Volver</a>
        </div>
    </nav>

    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2 class="titulo">Lista de Clases</h2>
            <p class="subtitulo">Administra las clases del sistema</p>
        </div>

        <div class="mb-4">
            <input type="text" id="buscador" class="form-control" placeholder="Buscar clase...">
        </div>

        <div class="registro-card p-4">

            <table class="table table-dark table-hover text-center align-middle" id="tablaClases">
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
                  
                </tbody>

            </table>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

requireAdmin();

async function cargarClases() {

    const response = await fetch('/api/clases');

    const data = await response.json();

    let tbody = document.getElementById("tbodyClases");

    tbody.innerHTML = "";

    data.forEach(clase => {

        let horaFormateada = clase.horario
            ? clase.horario.substring(0, 5)
            : "";

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
}

cargarClases();

</script>

</body>

</html>