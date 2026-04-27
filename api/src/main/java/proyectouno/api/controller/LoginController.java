package proyectouno.api.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import org.springframework.web.bind.annotation.RequestBody;

import proyectouno.api.dto.AuthResponse;
import proyectouno.api.dto.LoginRequest;
import proyectouno.api.entity.Rol;
import proyectouno.api.entity.Usuario;
import proyectouno.api.security.JwtUtil;
import proyectouno.api.service.UsuarioService;

@RestController
@RequestMapping("/registroCompleto")
@CrossOrigin(origins = "*")
public class LoginController {

    @Autowired
    private UsuarioService usuarioService;
    @Autowired

    private JwtUtil jwtUtil;

    @Autowired
    private PasswordEncoder passwordEncoder;


    @PostMapping("/login")
public AuthResponse login(@RequestBody LoginRequest request) {

    Usuario usuario = usuarioService
            .findByUserName(request.getUsername())
            .orElseThrow(() -> new RuntimeException("Usuario no encontrado"));

    if (!passwordEncoder.matches(request.getPassword(), usuario.getPassword())) {
        throw new RuntimeException("Contraseña incorrecta");
    }

    String token = jwtUtil.generateToken(
            org.springframework.security.core.userdetails.User
                    .withUsername(usuario.getUserName())
                    .password(usuario.getPassword())
                    .authorities("USER")
                    .build()
    );

    return new AuthResponse(token);
}

    @PostMapping("/registro")
    public Usuario procesarRegistro(@RequestBody Usuario usuario) {

        Rol rol = new Rol();
        rol.setIdRol(1);
        usuario.setRol(rol);
        usuario.setPassword(passwordEncoder.encode(usuario.getPassword()));

        return usuarioService.add(usuario);
    }

}
