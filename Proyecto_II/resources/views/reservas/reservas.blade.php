<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reserva</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reservas.css') }}">
</head>

<body>

    <div class="container-fluid px-5">

        <header class="header d-flex justify-content-between align-items-center py-3">
            <img src="{{ asset('img/logo.png') }}" class="logo-header">
            <div class="d-flex gap-2">

                <a href="{{ url('/clases/horarioClases') }}" class="btn btn-login-neon">Volver</a>
            </div>
        </header>

        <div class="card reserva-detalle p-4">

            <h2 class="text-center mb-4 titulo">Reserva tu espacio</h2>

            <div class="row">

                <div class="col-md-5">
                    <img src="{{ asset('img/reserva.png') }}" class="img-fluid rounded">
                </div>

                <div class="col-md-7">

                    @if (!$clase)
                        <p>No se encontró la clase</p>
                    @else

                        <h2>{{ $clase->nombre }}</h2>

                        <p>{{ $clase->descripcion }}</p>

                        <p>{{ $clase->diaSemana }}</p>

                        <p>{{ \Carbon\Carbon::parse($clase->horario)->format('H:i') }}</p>

                        <p class="{{ $clase->capacidad == 0 ? 'estado lleno' : 'estado disponible' }}">
                            {{ $clase->capacidad == 0
                                ? 'Clase llena'
                                : $clase->capacidad . ' cupos disponibles' }}
                        </p>

                        <form id="formReserva">

                            <input type="hidden" name="claseId" value="{{ $clase->idClase }}">

                            <button 
                                type="submit" 
                                class="btn btn-main w-100 mt-3"
                                {{ $clase->capacidad == 0 ? 'disabled' : '' }}>
                                Reservar
                            </button>

                        </form>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <footer class="footer mt-5 py-4 text-center">
        <div class="container">
            <img src="{{ asset('img/logo.png') }}" width="120" class="mb-2">
            <p>© 2026 VIKINGS</p>
        </div>
    </footer>

    <script src="{{ asset('js/auth.js') }}"></script>

    <script>

        requireAuth();

        function cambiarIdiomaReserva(locale) {
            setLocale(locale);
        }

        document.getElementById("formReserva")?.addEventListener("submit", function (e) {

            e.preventDefault();

            const claseId = document.querySelector("[name=claseId]").value;

            const data = {
                idClase: parseInt(claseId),
                fechaReserva: new Date().toISOString().slice(0, 10)
            };

            authFetch("/reservas", {
                method: "POST",
                body: JSON.stringify(data)
            })
            .then(res => {

                if (!res) return;

                if (!res.ok) {
                    return res.json().then(error => {
                        throw new Error(error.message);
                    });
                }

                return res.json();
            })

            .then(data => {
                alert(data?.message || "Reserva realizada");

                window.location.href = "/clases/horarioClases";

            })
            .catch(err => {
                alert("❌ " + err.message);
            });

        });

    </script>

</body>

</html>
