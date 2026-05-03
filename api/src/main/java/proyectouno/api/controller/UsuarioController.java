package proyectouno.api.controller;

import java.util.*;
import org.springframework.web.bind.annotation.*;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.web.server.ResponseStatusException;
import proyectouno.api.entity.Usuario;
import proyectouno.api.service.UsuarioService;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.tags.Tag;

@CrossOrigin(origins = "*") // Permitir acceso desde cualquier origen
@Tag(name = "Usuarios", description = "API para gestionar usuarios") // Grupo en Swagger
@RestController
@RequestMapping("/usuarios")
public class UsuarioController {
    @Autowired
    private UsuarioService usuarioService;

    @GetMapping
    @Operation(summary = "Obtener todos los usuarios", description = "Devuelve una lista de usuarios")
    public List<Usuario> get() {
        return usuarioService.get();
    }

    @GetMapping("/{id}")
    @Operation(summary = "Obtener un usuario por ID", description = "Busca un usuario en la base de datos según su ID")
    public Usuario getById(@PathVariable int id) {
        return usuarioService.getById(id).orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND));
    }

    @PostMapping
    @Operation(summary = "Crear una nueva categoría", description = "Agrega una nueva categoría a la base de datos")
    public Usuario add(@RequestBody Usuario usuario) {
        return usuarioService.add(usuario);
    }

    @Operation(summary = "Modificar un usuario", description = "Modifica un usuario existente en la base de datos")
    @PutMapping("/{id}")
    public Usuario update(@PathVariable int id, @RequestBody Usuario usuario) {
        return usuarioService.update(id, usuario);
    }

    @Operation(summary = "Eliminar un usuario", description = "Elimina un usuario de la base de datos")
    @DeleteMapping("/{id}")
    public void delete(@PathVariable int id) {
        usuarioService.delete(id);
    }
}
