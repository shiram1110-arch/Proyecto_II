<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Crear Clase</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/crearClase.css') }}">
</head>

<body>

<div class="d-flex justify-content-center align-items-center vh-100">

    <div class="registro-card p-4">

        <div class="text-center">
            <img src="{{ asset('img/logo.png') }}" class="logo" alt="logo">

            <h2 class="titulo">
                {{ isset($clase) ? 'Editar Clase' : 'Nueva Clase' }}
            </h2>

            <p class="subtitulo">
                Crea una clase en el sistema
            </p>
        </div>

        <form id="formClase">

            <input type="hidden" name="idClase" value="{{ $clase->idClase ?? '' }}">

            <div class="mb-3">
                <input type="text" name="nombre" class="form-control"
                       placeholder="Nombre de la clase"
                       value="{{ $clase->nombre ?? '' }}" required>
            </div>

            <div class="mb-3">
                <input type="text" name="descripcion" class="form-control"
                       placeholder="Descripción"
                       value="{{ $clase->descripcion ?? '' }}" required>
            </div>

            <div class="mb-3">
                <select name="diaSemana" class="form-control" required>
                    <option value="">Día de la semana</option>
                    <option>Lunes</option>
                    <option>Martes</option>
                    <option>Miércoles</option>
                    <option>Jueves</option>
                    <option>Viernes</option>
                    <option>Sábado</option>
                    <option>Domingo</option>
                </select>
            </div>

            <div class="mb-3">
                <input type="time" name="horario" class="form-control"
                       value="{{ $clase->horario ?? '' }}" required>
            </div>

            <div class="mb-3">
                <input type="number" name="capacidad" class="form-control"
                       min="1"
                       value="{{ $clase->capacidad ?? '' }}" required>
            </div>

            <button class="btn btn-main w-100" type="submit">
                Guardar Clase
            </button>

        </form>

    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>

<script>
requireAdmin();

const select = document.querySelector("[name=diaSemana]");
if (select && "{{ $clase->diaSemana ?? '' }}") {
    select.value = "{{ $clase->diaSemana ?? '' }}";
}

document.getElementById("formClase").addEventListener("submit", async (e) => {
    e.preventDefault();

    const id = document.querySelector("[name=idClase]").value;

    const data = {
        nombre: document.querySelector("[name=nombre]").value,
        descripcion: document.querySelector("[name=descripcion]").value,
        diaSemana: document.querySelector("[name=diaSemana]").value,
        horario: document.querySelector("[name=horario]").value,
        capacidad: Number(document.querySelector("[name=capacidad]").value)
    };

    let url = "/clases";
    let method = "POST";

    if (id) {
        url = `/clases/${id}`;
        method = "PUT";
    }

    try {
        const res = await authFetch(url, {
            method,
            body: JSON.stringify(data)
        });

        if (!res || !res.ok) {

            let errorText = await res.text();

            let message = "Error desconocido";

            try {
                const json = JSON.parse(errorText);
                message = json.message || message;
            } catch (e) {
                console.error("Respuesta no JSON:", errorText);
            }

            throw new Error(message);
        }

        alert("✅ Clase guardada correctamente");
        window.location.href = "/clases/clasesVista";

    } catch (err) {
        console.error(err);
        alert(err.message);
    }
});
</script>

</body>
</html>