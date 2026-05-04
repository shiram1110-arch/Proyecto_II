package proyectouno.api.controller;

import java.util.List;

import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.*;

import lombok.AllArgsConstructor;
import proyectouno.api.dto.ReservaDTO;
import proyectouno.api.entity.Usuario;
import proyectouno.api.service.ReservaService;
import proyectouno.api.service.UsuarioService;

@RestController
@RequestMapping("/api/reservas")
@AllArgsConstructor
public class ApiController {

    private ReservaService reservaService;
    private UsuarioService usuarioService;

    
    @GetMapping("/mis-clases")
    public List<ReservaDTO> getReservas(Authentication authentication) {

        if (authentication == null) {
            throw new RuntimeException("Usuario no autenticado");
        }

        String username = authentication.getName();

        Usuario usuario = usuarioService.findByUsername(username);

        return reservaService.getReservasPorUsuario(usuario.getIdUsuario());
    }
}