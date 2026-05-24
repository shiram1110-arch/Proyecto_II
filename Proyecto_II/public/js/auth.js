const API_URL = "/api";

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

function isAuthenticated() {
    return !!getToken();
}

// 🔥 FIX: seguro
function removeToken() {
    localStorage.removeItem("token");
}

function removeUser() {
    localStorage.removeItem("user");
}

async function authFetch(url, options = {}) {
    const token = getToken();

    const headers = {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        ...(options.headers || {})
    };

    if (token) {
        headers.Authorization = `Bearer ${token}`;
    }

    const response = await fetch(`${API_URL}${url}`, {
        ...options,
        headers
    });

    // DEBUG IMPORTANTE
    console.log("URL:", `${API_URL}${url}`);
    console.log("HEADERS:", headers);

    if (response.status === 401) {
        console.log("TOKEN INVALIDO O NO RECONOCIDO");
        removeToken();
        removeUser();
        window.location.href = "/login";
    }

    return response;
}
function logout() {

    fetch("/api/logout", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + getToken(),
            "Accept": "application/json"
        }
    }).finally(() => {
        removeToken();
        removeUser();
        window.location.href = "/inicio";
    });
}

async function loadUser() {

    const token = getToken();

    if (!token) return null;

    const res = await fetch("/api/perfil", {
        headers: {
            "Authorization": `Bearer ${token}`,
            "Accept": "application/json"
        }
    });

    const text = await res.text();

    if (!res.ok) {
        console.error("ERROR PERFIL", res.status);
        return null;
    }

    const data = JSON.parse(text);

    setUser(data.user);
    return data.user;
}

function isAdmin() {
    const user = getUser();
    return user?.rol?.idRol === 1;
}

function isUser() {
    const user = getUser();
    return user?.rol?.idRol === 2;
}

function getNombre() {
    const user = getUser();

    if (!user) return;

    const nombre = user.nombre || "";
    const apellido = user.apellido1 || "";

    const span = document.getElementById("nombreUsuario");

    if (span) {
        span.textContent = `¡Hola, ${nombre} ${apellido}! 👋`;
    }
}

/* =====================================================
   🔥 FALTABA ESTO (CRÍTICO)
===================================================== */

function requireAdmin() {

    const user = getUser();

    if (!user) {
        window.location.href = "/login";
        return;
    }

    if (user.rol?.idRol !== 1) {
        alert("No tienes permisos de administrador");
        window.location.href = "/inicio";
    }
}

function requireAuth() {

    if (!isAuthenticated()) {
        window.location.href = "/login";
    }
}