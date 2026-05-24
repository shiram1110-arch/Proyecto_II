<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">
</head>

<body>

    <nav class="navbar header px-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" class="logo me-2">
            <h4 class="titulo m-0">Administración de Usuarios</h4>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-main"
                data-url="{{ url('/registro') }}"
                onclick="window.location.href=this.dataset.url">
                + Crear Usuario
            </button>

            <a href="{{ url('/admin/adminDashboard') }}" class="btn btn-login-neon">
                Volver
            </a>
        </div>
    </nav>

    <div class="container mt-5">

        <div class="text-center mb-4">
            <h2 class="titulo">Lista de Usuarios</h2>
            <p class="subtitulo">Administra los usuarios del sistema</p>
        </div>

        <div class="registro-card p-4">

            <table class="table table-dark table-hover text-center align-middle">

                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->nombre }}</td>

                            <td>
                                {{ $usuario->apellidoUno }} {{ $usuario->apellidoDos }}
                            </td>

                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefono }}</td>
                            <td>{{ $usuario->userName }}</td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ url('/usuarios/' . $usuario->idUsuario . '/editar') }}"
                                       class="btn btn-sm btn-primary">
                                        Editar
                                    </a>

                                    <button class="btn btn-sm btn-danger"
                                            data-url="{{ $usuario->idUsuario }}"
                                            onclick="eliminarUsuario(this.dataset.url)">
                                        Eliminar
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>
        requireAdmin();

        function eliminarUsuario(id) {

            if (!confirm("¿Seguro que deseas eliminar este usuario?")) return;

            authFetch(`/usuarios/${id}`, {
                method: "DELETE"
            })
            .then(res => {
                if (!res || !res.ok) throw new Error("Error al eliminar usuario");

                alert("✅ Usuario eliminado");
                location.reload();
            })
            .catch(err => alert(err.message));
        }
    </script>

</body>

</html>