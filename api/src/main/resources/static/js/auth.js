function getToken() {
    return localStorage.getItem("token");
}

function setToken(token) {
    localStorage.setItem("token", token);
}

function getUser() {
    return JSON.parse(localStorage.getItem("user"));
}

function setUser(user) {
    localStorage.setItem("user", JSON.stringify(user));
}

function logout() {

    const token = getToken();

    fetch("/api/logout", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + token,
            "Accept": "application/json"
        }
    }).finally(() => {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        window.location.href = "/inicio";
    });
}

function isAuthenticated() {
    return !!getToken();
}

/*
|--------------------------------------------------------------------------
| FETCH CON AUTH (SANCTUM)
|--------------------------------------------------------------------------
*/
function authFetch(url, options = {}) {

    const token = getToken();

    const headers = {
        "Accept": "application/json",
        ...(options.headers || {})
    };

    if (token) {
        headers["Authorization"] = "Bearer " + token;
    }

    return fetch(url, {
        ...options,
        headers
    }).then(async res => {

        if (res.status === 401) {
            console.log("Sesión expirada");
            logout();
            return;
        }

        return res;
    });
}

/*
|--------------------------------------------------------------------------
| CARGAR USUARIO LOGUEADO
|--------------------------------------------------------------------------
*/
async function loadUser() {

    const token = getToken();
    if (!token) return null;

    const res = await authFetch("/api/perfil");

    if (!res || !res.ok) {
        console.error("ERROR PERFIL");
        return null;
    }

    const data = await res.json();

    console.log("PERFIL API:", data);

    const user = data.user;

    setUser(user);

    return user;
}

/*
|--------------------------------------------------------------------------
| ROLES (CORRECTO PARA LARAVEL)
|--------------------------------------------------------------------------
*/
function isAdmin() {
    const user = getUser();
    return Number(user?.idRol ?? user?.rol?.idRol) === 1;
}

function isUser() {
    const user = getUser();
    return Number(user?.idRol ?? user?.rol?.idRol) === 2;
}

/*
|--------------------------------------------------------------------------
| PROTEGER RUTAS
|--------------------------------------------------------------------------
*/
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = "/login";
    }
}

function requireAdmin() {
    requireAuth();

    if (!isAdmin()) {
        alert("⛔ No tienes permisos de ADMIN");
        window.location.href = "/inicio";
    }
}

/*
|--------------------------------------------------------------------------
| MOSTRAR NOMBRE EN HEADER
|--------------------------------------------------------------------------
*/
function getNombre() {

    document.addEventListener("DOMContentLoaded", async () => {

        const user = getUser();

        if (!user) {
            console.log("No user logged");
            return;
        }

        const nombreCompleto =
            `${user.nombre ?? ""} ${user.apellidoUno ?? ""}`;

        const elemento = document.getElementById("nombreUsuario");

        if (!elemento) return;

        const ruta = window.location.pathname;

        if (ruta.includes("inicio")) {
            elemento.textContent = `¡Hola, ${nombreCompleto}! 👋`;
        }

        else if (ruta.includes("historial")) {
            elemento.textContent = `Historial de ${nombreCompleto}`;
        }

        else {
            elemento.textContent = nombreCompleto;
        }
    });
}