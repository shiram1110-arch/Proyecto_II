
// 🔓 Obtener token
function getToken() {
    return localStorage.getItem("token");
}

// 🔓 Guardar token
function setToken(token) {
    localStorage.setItem("token", token);
}

// 🚪 Logout global
function logout() {
    localStorage.removeItem("token");
    window.location.href = "/inicio";
}

// 🔓 Decodificar JWT
function parseJwt(token) {
    try {
        return JSON.parse(atob(token.split('.')[1]));
    } catch (e) {
        return null;
    }
}

// 🔐 Validar si hay sesión
function isAuthenticated() {
    const token = getToken();
    return !!token;
}

// 🔐 Obtener payload
function getPayload() {
    const token = getToken();
    if (!token) return null;
    return parseJwt(token);
}

// 🔐 Obtener roles
function getRoles() {
    const payload = getPayload();
    if (!payload) return [];
    return payload.roles || [];
}

// 🔐 Validar ADMIN
function isAdmin() {
    return getRoles().includes("ROLE_ADMIN");
}

// 🔐 Validar USER
function isUser() {
    return getRoles().includes("ROLE_USER");
}

// 🔒 Proteger páginas (requiere login)
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = "/login";
    }
}

// 🔒 Proteger ADMIN
function requireAdmin() {
    requireAuth();

    if (!isAdmin()) {
        alert("⛔ No tienes permisos de ADMIN");
        window.location.href = "/inicio";
    }
}

// 🔒 Fetch con token automático
function authFetch(url, options = {}) {
    const token = getToken();

    const headers = {
        "Content-Type": "application/json",
        ...(options.headers || {}),
        ...(token && { "Authorization": "Bearer " + token })
    };

    return fetch(url, {
        ...options,
        headers
    }).then(res => {

        if (res.status === 401) {
            alert("Sesión expirada");
            logout();
            return;
        }

        if (res.status === 403) {
            throw new Error("Forbidden");
        }

        return res;
    });
}
function getNombre() {
    document.addEventListener("DOMContentLoaded", async () => {

        const response = await authFetch("/api/usuarios/me");

        if (!response.ok) return;

        const usuario = await response.json();

        const nombreCompleto = `${usuario.nombre} ${usuario.apellidoUno}`;

        const elemento = document.getElementById("nombreUsuario");

        // 🔥 Detectar página actual
        const ruta = window.location.pathname;

        // 🏠 INICIO
        if (ruta.includes("inicio")) {
            elemento.textContent = `¡Hola, ${nombreCompleto}! 👋`;
        }

        // 📊 HISTORIAL
        else if (ruta.includes("historial")) {
            elemento.textContent = `Historial de ${nombreCompleto}`;
        }

        // 🔥 DEFAULT (por si acaso)
        else {
            elemento.textContent = nombreCompleto;
        }
    });
}