package proyectouno.api.controller;

import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import proyectouno.api.dto.UsuarioDTO;
import proyectouno.api.entity.Usuario;
import proyectouno.api.service.UsuarioService;

@RestController
@RequestMapping("/api/usuarios")
public class ApiNombreController {
    private final UsuarioService usuarioService;

    public ApiNombreController(UsuarioService usuarioService) {
        this.usuarioService = usuarioService;
    }

    @GetMapping("/me")
    public UsuarioDTO getMiUsuario(Authentication authentication) {

        String username = authentication.getName();

        Usuario u = usuarioService.findByUsername(username);

        UsuarioDTO dto = new UsuarioDTO();
        dto.nombre = u.getNombre();
        dto.apellidoUno = u.getApellidoUno();
        dto.userName = u.getUserName();

        return dto;
    }
}
