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

    public Usuario add(Usuario usuario) {

        validarUsernameUnico(usuario.getUserName());

        try {
            usuario.setRol(obtenerRolParaCreacion(usuario));
            usuario.setPassword(passwordEncoder.encode(usuario.getPassword()));

            return usuarioRepository.save(usuario);

        } catch (Exception e) {
            throw new ResponseStatusException(
                    HttpStatus.BAD_REQUEST,
                    "El username o email ya existe");
        }
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

    public Usuario update(int id, Usuario usuario) {

        Usuario existingUsuario = obtenerUsuarioPorId(id);

        actualizarUsernameSiCambio(existingUsuario, usuario);
        actualizarDatosBasicos(existingUsuario, usuario);
        actualizarPasswordSiViene(existingUsuario, usuario);
        actualizarRolSiEsAdmin(existingUsuario, usuario);

        return usuarioRepository.save(existingUsuario);
    }

    public Usuario findByUsername(String username) {
        return usuarioRepository.findByUserName(username)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Usuario no encontrado"));
    }

    private void validarUsernameUnico(String username) {
        if (usuarioRepository.findByUserName(username).isPresent()) {
            throw new ResponseStatusException(
                    HttpStatus.BAD_REQUEST,
                    "El nombre de usuario ya existe");
        }
    }

    private Usuario obtenerUsuarioPorId(int id) {
        return usuarioRepository.findById(id)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Usuario no encontrado"));
    }

    private void actualizarUsernameSiCambio(Usuario existing, Usuario nuevo) {
        if (!existing.getUserName().equals(nuevo.getUserName())) {
            validarUsernameUnico(nuevo.getUserName());
            existing.setUserName(nuevo.getUserName());
        }
    }

    private void actualizarDatosBasicos(Usuario existing, Usuario nuevo) {
        existing.setNombre(nuevo.getNombre());
        existing.setApellidoUno(nuevo.getApellidoUno());
        existing.setApellidoDos(nuevo.getApellidoDos());
        existing.setEmail(nuevo.getEmail());
        existing.setTelefono(nuevo.getTelefono());
    }

    private void actualizarPasswordSiViene(Usuario existing, Usuario nuevo) {
        if (nuevo.getPassword() != null && !nuevo.getPassword().isEmpty()) {
            existing.setPassword(passwordEncoder.encode(nuevo.getPassword()));
        }
    }

    private void actualizarRolSiEsAdmin(Usuario existing, Usuario nuevo) {
        if (esAdmin() && nuevo.getRol() != null) {
            Rol rol = rolRepository.findById(nuevo.getRol().getIdRol())
                    .orElseThrow(() -> new ResponseStatusException(
                            HttpStatus.NOT_FOUND, "Rol no existe"));

            existing.setRol(rol);
        }
    }

    private boolean esAdmin() {
        Authentication auth = SecurityContextHolder.getContext().getAuthentication();

        return auth != null && auth.getAuthorities().stream()
                .anyMatch(a -> a.getAuthority().equals("ROLE_ADMIN"));
    }

    private Rol obtenerRolParaCreacion(Usuario usuario) {

        if (esAdmin() && usuario.getRol() != null && usuario.getRol().getIdRol() != null) {
            return rolRepository.findById(usuario.getRol().getIdRol())
                    .orElseThrow(() -> new ResponseStatusException(
                            HttpStatus.NOT_FOUND, "Rol no existe"));
        }

        return rolRepository.findById(1)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Rol USER no existe"));
    }
}