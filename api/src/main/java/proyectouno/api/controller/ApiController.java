package proyectouno.api.controller;

import java.util.List;

import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import proyectouno.api.entity.Reserva;
import proyectouno.api.entity.Usuario;
import proyectouno.api.service.ReservaService;
import proyectouno.api.service.UsuarioService;

@RestController
@RequestMapping("/api/reservas")
public class ApiController {

    private final ReservaService reservaService;
    private final UsuarioService usuarioService;

    public ApiController(ReservaService reservaService, UsuarioService usuarioService) {
        this.reservaService = reservaService;
        this.usuarioService = usuarioService;
    }

    @GetMapping("/mis-clases")
    public List<Reserva> getReservas(Authentication authentication) {
        if (authentication == null) {
            throw new RuntimeException("No autenticado (JWT no está funcionando)");
        }
        String user = authentication.getName();

        Usuario usuario = usuarioService.findByUsername(user);

        return reservaService.getReservasPorUsuario(usuario.getIdUsuario());
    }
}