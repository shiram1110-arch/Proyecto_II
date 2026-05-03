package proyectouno.api.service;

import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.entity.Rol;
import proyectouno.api.entity.Usuario;
import proyectouno.api.repository.RolRepository;
import proyectouno.api.repository.UsuarioRepository;

@Service
@AllArgsConstructor
public class UsuarioService {

    private UsuarioRepository usuarioRepository;
    private RolRepository rolRepository;
    private PasswordEncoder passwordEncoder;

    // 🔥 CREATE SEGURO
    public Usuario add(Usuario usuario) {

        Authentication auth = SecurityContextHolder.getContext().getAuthentication();

        boolean esAdmin = false;

        if (auth != null && auth.getAuthorities() != null) {
            esAdmin = auth.getAuthorities().stream()
                    .anyMatch(a -> a.getAuthority().equals("ROLE_ADMIN"));
        }

        Rol rol;

        // 🔥 SI ES ADMIN → puede elegir rol
        if (esAdmin && usuario.getRol() != null && usuario.getRol().getIdRol() != null) {

            rol = rolRepository.findById(usuario.getRol().getIdRol())
                    .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Rol no existe"));

        } 
        // 🔥 SI NO → SIEMPRE USER
        else {

            rol = rolRepository.findById(1) // ROLE_USER
                    .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Rol USER no existe"));
        }

        usuario.setRol(rol);

        usuario.setPassword(passwordEncoder.encode(usuario.getPassword()));

        return usuarioRepository.save(usuario);
    }

    public List<Usuario> get() {
        return usuarioRepository.findAll();
    }

    public Optional<Usuario> getById(int id) {
        return usuarioRepository.findById(id);
    }

    public void delete(int id) {
        usuarioRepository.deleteById(id);
    }

    // 🔥 UPDATE SEGURO
    public Usuario update(int id, Usuario usuario) {

        Usuario existingUsuario = usuarioRepository.findById(id)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Usuario no encontrado"));

        existingUsuario.setNombre(usuario.getNombre());
        existingUsuario.setApellidoUno(usuario.getApellidoUno());
        existingUsuario.setApellidoDos(usuario.getApellidoDos());
        existingUsuario.setEmail(usuario.getEmail());
        existingUsuario.setTelefono(usuario.getTelefono());

        // 🔥 SOLO actualizar password si viene
        if (usuario.getPassword() != null && !usuario.getPassword().isEmpty()) {
            existingUsuario.setPassword(passwordEncoder.encode(usuario.getPassword()));
        }

        // 🔥 SOLO ADMIN puede cambiar rol
        Authentication auth = SecurityContextHolder.getContext().getAuthentication();

        boolean esAdmin = auth.getAuthorities().stream()
                .anyMatch(a -> a.getAuthority().equals("ROLE_ADMIN"));

        if (esAdmin && usuario.getRol() != null) {
            Rol rol = rolRepository.findById(usuario.getRol().getIdRol())
                    .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Rol no existe"));

            existingUsuario.setRol(rol);
        }

        return usuarioRepository.save(existingUsuario);
    }

    public Usuario findByUsername(String username) {
        return usuarioRepository.findByUserName(username)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Usuario no encontrado"));
    }

}
