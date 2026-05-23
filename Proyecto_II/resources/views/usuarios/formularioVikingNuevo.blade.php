<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registro</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet"
          href="{{ asset('css/formularioVikingNuevo.css') }}">

</head>

<body>

    <br><br><br>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card p-4 registro-card">

            {{-- Título --}}
            <div class="text-center mb-3">

                <h3 class="fw-bold titulo">
                    Datos de usuario
                </h3>

            </div>

            {{-- Formulario --}}
            <form id="registro">

                {{-- ID --}}
                <input type="hidden"
                       id="idUsuario"
                       name="idUsuario"
                       value="{{ $usuario->idUsuario ?? '' }}">

                {{-- Nombre --}}
                <input type="text"
                       name="nombre"
                       class="form-control mb-3"
                       placeholder="Nombre"
                       value="{{ $usuario->nombre ?? '' }}"
                       required>

                {{-- Primer apellido --}}
                <input type="text"
                       name="apellidoUno"
                       class="form-control mb-3"
                       placeholder="Primer apellido"
                       value="{{ $usuario->apellidoUno ?? '' }}"
                       required>

                {{-- Segundo apellido --}}
                <input type="text"
                       name="apellidoDos"
                       class="form-control mb-3"
                       placeholder="Segundo apellido"
                       value="{{ $usuario->apellidoDos ?? '' }}"
                       required>

                {{-- Teléfono --}}
                <input type="tel"
                       name="telefono"
                       class="form-control mb-3"
                       placeholder="Teléfono"
                       value="{{ $usuario->telefono ?? '' }}"
                       required>

                {{-- Email --}}
                <input type="email"
                       name="email"
                       class="form-control mb-3"
                       placeholder="Correo electrónico"
                       value="{{ $usuario->email ?? '' }}"
                       required>

                {{-- Título registro --}}
                <div class="text-center mb-3">

                    <h3 class="fw-bold titulo">
                        Registro de usuario
                    </h3>

                </div>

                {{-- Username --}}
                <input type="text"
                       id="username"
                       name="username"
                       class="form-control mb-1"
                       placeholder="Usuario"
                       value="{{ $usuario->userName ?? '' }}"
                       required>

                {{-- Error username --}}
                <div id="usernameError"
                     class="text-danger mb-3"
                     style="display:none;">

                    El usuario ya existe
                </div>

                {{-- Password --}}
                <input type="password"
                       name="password"
                       class="form-control mb-3"
                       placeholder="Contraseña"
                       {{ isset($usuario) ? '' : 'required' }}>

                {{-- Botón --}}
                <button type="submit"
                        class="btn btn-main w-100">

                    {{ isset($usuario) ? 'Actualizar usuario' : 'Crear usuario' }}

                </button>

                {{-- Logo --}}
                <div class="text-center mb-4">

                    <br>

                    <img src="{{ asset('img/logo.png') }}"
                         alt="logo"
                         class="logo">

                </div>

            </form>

        </div>

    </div>

    {{-- JS --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

        const usernameInput = document.getElementById("username");

        const usernameError = document.getElementById("usernameError");

        const idUsuario =
            document.getElementById("idUsuario").value;

        // Validar username
        usernameInput.addEventListener("blur", async () => {

            const username =
                usernameInput.value.trim();

            if (!username) return;

            try {

                const res = await fetch(
                    `/usuarios/buscar/${encodeURIComponent(username)}`
                );

                if (!res.ok) return;

                const data = await res.json();

                const existe = data.some(u =>

                    u.userName.toLowerCase() ===
                    username.toLowerCase()

                    && u.idUsuario != idUsuario
                );

                usernameError.style.display =
                    existe ? "block" : "none";

            } catch (e) {

                console.error(e);
            }

        });

        // Submit formulario
        document.getElementById("registro")
            .addEventListener("submit", async function (e) {

                e.preventDefault();

                let rolId = isAdmin() ? 2 : 1;

                const data = {

                    userName: usernameInput.value,

                    password:
                        document.querySelector("[name=password]").value,

                    nombre:
                        document.querySelector("[name=nombre]").value,

                    apellidoUno:
                        document.querySelector("[name=apellidoUno]").value,

                    apellidoDos:
                        document.querySelector("[name=apellidoDos]").value,

                    telefono:
                        document.querySelector("[name=telefono]").value,

                    email:
                        document.querySelector("[name=email]").value,

                    rol: {
                        idRol: rolId
                    }
                };

                let url = "/usuarios";

                let method = "POST";

                // Editar usuario
                if (idUsuario) {

                    url = `/usuarios/${idUsuario}`;

                    method = "PUT";

                    // No enviar password vacía
                    if (!data.password) {

                        delete data.password;
                    }
                }

                try {

                    const res = await fetch(url, {

                        method: method,

                        headers: {

                            "Content-Type": "application/json",

                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content")
                        },

                        body: JSON.stringify(data)
                    });

                    if (!res.ok) {

                        const error = await res.text();

                        if (
                            error.toLowerCase().includes("usuario")
                            ||
                            error.toLowerCase().includes("username")
                        ) {

                            usernameError.style.display = "block";

                            return;
                        }

                        throw new Error(
                            error || "Error al guardar usuario"
                        );
                    }

                    alert("✅ Usuario guardado");

                    window.location.href =
                        isAdmin()
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