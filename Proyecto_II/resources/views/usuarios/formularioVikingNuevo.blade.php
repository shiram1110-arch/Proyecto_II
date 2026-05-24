<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="{{ asset('css/formularioVikingNuevo.css') }}">

</head>

<body>

<br><br><br>

<div class="container d-flex justify-content-center align-items-center min-vh-100">

    <div class="card p-4 registro-card">

        <div class="text-center mb-3">
            <h3 class="fw-bold titulo">Datos de usuario</h3>
        </div>

        <form id="registro">

            <input type="hidden"
                   id="idUsuario"
                   value="{{ $usuario->idUsuario ?? '' }}">

            <input type="text"
                   name="nombre"
                   class="form-control mb-3"
                   placeholder="Nombre"
                   value="{{ $usuario->nombre ?? '' }}"
                   required>

            <input type="text"
                   name="apellidoUno"
                   class="form-control mb-3"
                   placeholder="Primer apellido"
                   value="{{ $usuario->apellidoUno ?? '' }}"
                   required>

            <input type="text"
                   name="apellidoDos"
                   class="form-control mb-3"
                   placeholder="Segundo apellido"
                   value="{{ $usuario->apellidoDos ?? '' }}">

            <input type="tel"
                   name="telefono"
                   class="form-control mb-3"
                   placeholder="Teléfono"
                   value="{{ $usuario->telefono ?? '' }}">

            <input type="email"
                   name="email"
                   class="form-control mb-3"
                   placeholder="Correo"
                   value="{{ $usuario->email ?? '' }}"
                   required>

            <div class="text-center mb-3">
                <h3 class="fw-bold titulo">Registro de usuario</h3>
            </div>

            <input type="text"
                   id="userName"
                   name="userName"
                   class="form-control mb-3"
                   placeholder="Usuario"
                   value="{{ $usuario->userName ?? '' }}"
                   required>

            <div id="usernameError"
                 class="text-danger mb-3"
                 style="display:none;">
                El usuario ya existe
            </div>

            <input type="password"
                   name="password"
                   class="form-control mb-3"
                   placeholder="Contraseña"
                   {{ isset($usuario) ? '' : 'required' }}>

            <button type="submit" class="btn btn-main w-100">
                {{ isset($usuario) ? 'Actualizar usuario' : 'Crear usuario' }}
            </button>

        </form>

    </div>

</div>

<script src="{{ asset('js/auth.js') }}"></script>

<script>

const token = localStorage.getItem("token");

const idUsuario = document.getElementById("idUsuario").value;

const usernameInput =
    document.getElementById("userName");

const usernameError =
    document.getElementById("usernameError");

document.getElementById("registro").addEventListener("submit", async (e) => {

    e.preventDefault();

    usernameError.style.display = "none";

    let rolId = 1;

    const data = {

        userName: usernameInput.value,
        nombre: document.querySelector("[name=nombre]").value,
        apellidoUno: document.querySelector("[name=apellidoUno]").value,
        apellidoDos: document.querySelector("[name=apellidoDos]").value,
        telefono: document.querySelector("[name=telefono]").value,
        email: document.querySelector("[name=email]").value,
        idRol: rolId
    };

    const password =
        document.querySelector("[name=password]").value;

    if (password.trim() !== "") {
        data.password = password;
    }

    let url = "/api/usuarios";
    let method = "POST";

    if (idUsuario) {
        url = `/api/usuarios/${idUsuario}`;
        data._method = "PUT"; // 🔥 FIX SIN CAMBIAR TU FLUJO
    }

    try {

        const res = await fetch(url, {

            method,

            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Bearer " + token
            },

            body: JSON.stringify(data)
        });

        const result = await res.json();

        if (!res.ok) {

            console.log(result);

            if (result.errors && result.errors.userName) {
                usernameError.innerText = result.errors.userName[0];
                usernameError.style.display = "block";
            }

            throw new Error(result.message || "Error al guardar usuario");
        }

        alert(
            idUsuario
                ? "Usuario actualizado correctamente"
                : "Usuario creado correctamente"
        );

        window.location.href = isAdmin()
            ? "/usuariosVista"
            : "/inicio";

    } catch (err) {

        console.error(err);
        alert(err.message);
    }

});

</script>

</body>
</html>