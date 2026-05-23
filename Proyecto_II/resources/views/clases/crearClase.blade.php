<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    {{-- CSRF Laravel --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Crear Clase</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/crearClase.css') }}">
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">

        <div class="registro-card p-4">

            {{-- Header --}}
            <div class="text-center">

                <img src="{{ asset('img/logo.png') }}"
                     alt="logo"
                     class="logo">

                <h2 class="titulo">
                    {{ isset($clase) ? 'Editar Clase' : 'Nueva Clase' }}
                </h2>

                <p class="subtitulo">
                    Crea una clase en el sistema
                </p>

            </div>

            {{-- Formulario --}}
            <form id="formClase">

                {{-- ID oculto --}}
                <input type="hidden"
                       name="idClase"
                       value="{{ $clase->idClase ?? '' }}">

                {{-- Nombre --}}
                <div class="mb-3">

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Nombre de la clase"
                           value="{{ $clase->nombre ?? '' }}"
                           required>

                </div>

                {{-- Descripción --}}
                <div class="mb-3">

                    <input type="text"
                           name="descripcion"
                           class="form-control"
                           placeholder="Descripción"
                           value="{{ $clase->descripcion ?? '' }}"
                           required>

                </div>

                {{-- Día --}}
                <div class="mb-3">

                    <select name="diaSemana"
                            class="form-control"
                            required>

                        <option value="">Día de la semana</option>

                        <option value="Lunes"
                            {{ ($clase->diaSemana ?? '') == 'Lunes' ? 'selected' : '' }}>
                            Lunes
                        </option>

                        <option value="Martes"
                            {{ ($clase->diaSemana ?? '') == 'Martes' ? 'selected' : '' }}>
                            Martes
                        </option>

                        <option value="Miércoles"
                            {{ ($clase->diaSemana ?? '') == 'Miércoles' ? 'selected' : '' }}>
                            Miércoles
                        </option>

                        <option value="Jueves"
                            {{ ($clase->diaSemana ?? '') == 'Jueves' ? 'selected' : '' }}>
                            Jueves
                        </option>

                        <option value="Viernes"
                            {{ ($clase->diaSemana ?? '') == 'Viernes' ? 'selected' : '' }}>
                            Viernes
                        </option>

                        <option value="Sábado"
                            {{ ($clase->diaSemana ?? '') == 'Sábado' ? 'selected' : '' }}>
                            Sábado
                        </option>

                        <option value="Domingo"
                            {{ ($clase->diaSemana ?? '') == 'Domingo' ? 'selected' : '' }}>
                            Domingo
                        </option>

                    </select>

                </div>

                {{-- Horario --}}
                <div class="mb-3">

                    <input type="time"
                           name="horario"
                           class="form-control"
                           value="{{ $clase->horario ?? '' }}"
                           required>

                </div>

                {{-- Capacidad --}}
                <div class="mb-3">

                    <input type="number"
                           name="capacidad"
                           class="form-control"
                           placeholder="Capacidad"
                           value="{{ $clase->capacidad ?? '' }}"
                           min="1"
                           required>

                </div>

                {{-- Botón --}}
                <button type="submit"
                        class="btn btn-main w-100">

                    Guardar Clase
                </button>

            </form>

        </div>

    </div>

    {{-- JS --}}
    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

        requireAdmin();

        document.getElementById("formClase")
            .addEventListener("submit", function (e) {

                e.preventDefault();

                const id = document.querySelector("[name=idClase]").value;

                const data = {

                    nombre: document.querySelector("[name=nombre]").value,

                    descripcion: document.querySelector("[name=descripcion]").value,

                    diaSemana: document.querySelector("[name=diaSemana]").value,

                    horario: document.querySelector("[name=horario]").value,

                    capacidad: parseInt(
                        document.querySelector("[name=capacidad]").value
                    )

                };

                let url = "/clases";
                let method = "POST";

                // Editar
                if (id) {

                    url = `/clases/${id}`;

                    method = "PUT";
                }

                fetch(url, {

                    method: method,

                    headers: {

                        "Content-Type": "application/json",

                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content")
                    },

                    body: JSON.stringify(data)

                })

                .then(res => {

                    if (!res.ok) {

                        return res.json().then(error => {

                            throw new Error(
                                error.message || "Error al guardar"
                            );
                        });
                    }

                    return res.json();
                })

                .then(() => {

                    alert("✅ Clase guardada");

                    window.location.href = "/clasesVista";
                })

                .catch(err => {

                    console.error(err);

                    alert(err.message);
                });

            });

    </script>

</body>

</html>