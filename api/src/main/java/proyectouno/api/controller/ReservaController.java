package proyectouno.api.controller;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;
import org.springframework.security.core.Authentication; // 🔥 IMPORTANTE

import io.swagger.v3.oas.annotations.Operation;
import proyectouno.api.dto.ReservaDTO;
import proyectouno.api.entity.*;
import proyectouno.api.service.*;

import io.swagger.v3.oas.annotations.tags.Tag;

@CrossOrigin(origins = "*")
@Tag(name = "Reservas", description = "API para gestionar reservas")
@RestController
@RequestMapping("/api/reservas")
public class ReservaController {

    @Autowired
    private ReservaService reservaService;

    // 🔥 AGREGADO
    @Autowired
    private UsuarioService usuarioService;

    // 🔥 AGREGADO
    @Autowired
    private ClaseService claseService;

    @GetMapping
    @Operation(summary = "Obtener todas las reservas", description = "Devuelve una lista de reservas")
    public List<Reserva> get() {
        return reservaService.get();
    }

    @GetMapping("/{id}")
    @Operation(summary = "Obtener una reserva por ID", description = "Busca una reserva en la base de datos según su ID")
    public Reserva getById(@PathVariable int id) {
        return reservaService.getById(id)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND));
    }

    @PostMapping
    @Operation(summary = "Crear una nueva reserva", description = "Agrega una nueva reserva a la base de datos")
    public Reserva add(@RequestBody Reserva reserva, Authentication auth) { // 🔥 AGREGADO auth

        // 🔐 obtener usuario desde JWT
        String username = auth.getName();

        Usuario usuario = usuarioService.findByUsername(username);

        if (usuario == null) {
            throw new ResponseStatusException(HttpStatus.NOT_FOUND, "Usuario no encontrado");
        }

        // 🔥 asignar usuario
        reserva.setUsuario(usuario);

        // 🔥 validar y asignar clase real
        Clase clase = claseService.getById(reserva.getClase().getIdClase())
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Clase no encontrada"));

        reserva.setClase(clase);

        return reservaService.add(reserva);
    }

    @Operation(summary = "Modificar una reserva", description = "Modifica una  reserva existente en la base de datos")
    @PutMapping("/{id}")
    public Reserva update(@PathVariable int id, @RequestBody Reserva reserva) {
        return reservaService.update(id, reserva);
    }

    @Operation(summary = "Eliminar una reserva", description = "Elimina una reserva de la base de datos")
    @DeleteMapping("/{id}")
    public void delete(@PathVariable int id) {
        reservaService.delete(id);
    }

    @GetMapping("/estado/{estado}")
    public List<ReservaDTO> getByEstado(@PathVariable String estado) {
        return reservaService.getByEstadoDTO(estado);
    }

    @PutMapping("/cancelar/{id}")
    public Reserva cancelar(@PathVariable int id) {
        return reservaService.cancelarReserva(id);
    }

}