<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow-lg p-4 rounded-4" style="width: 350px;">

            <div class="text-center mb-4">
                <h3 class="fw-bold titulo">Bienvenido Viking</h3>
            </div>

            <form id="loginForm">

                <div class="form-floating mb-3">
                    <input type="text" id="username" class="form-control" placeholder="Usuario" required>
                    <label>Usuario</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="password" class="form-control" placeholder="Contraseña" required>
                    <label>Contraseña</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary rounded-3">
                        Iniciar sesión
                    </button>

                    <div class="text-center mt-3">
                        <span>¿No tienes cuenta?</span>
                        <a href="{{ url('/registro') }}" class="link-light fw-bold">
                            Crear cuenta
                        </a>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <br>
                    <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo">
                </div>

            </form>

        </div>

    </div>

    <script src="{{ asset('js/auth.js') }}"></script>


    <script>
        document
            .getElementById("loginForm")
            .addEventListener("submit", async function(e) {

                e.preventDefault();

                const userName = document.getElementById("username").value;
                const password = document.getElementById("password").value;

                try {

                    const response = await fetch("/api/login", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },

                        body: JSON.stringify({
                            userName,
                            password
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || "Credenciales inválidas");
                    }

                    setToken(data.token);

                    if (data.usuario) {
                        setUser(data.usuario);
                    }

                    window.location.href = "/inicio";

                } catch (error) {

                    console.error(error);
                    alert(error.message);
                }
            });
    </script>
</body>

</html>