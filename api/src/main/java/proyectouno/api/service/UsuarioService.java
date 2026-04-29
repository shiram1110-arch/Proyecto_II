package proyectouno.api.service;

import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.*;
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

    if (usuario.getRol() == null || usuario.getRol().getIdRol() == null) {
        throw new ResponseStatusException(HttpStatus.BAD_REQUEST, "El rol es obligatorio");
    }

    Rol rol = rolRepository.findById(usuario.getRol().getIdRol())
            .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Rol no existe"));

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

    public Usuario update(int id, Usuario usuario) {
        Optional<Usuario> existingUsuario = usuarioRepository.findById(id);
        if (existingUsuario.isPresent()) {
            Usuario updateUsuario = existingUsuario.get();
            updateUsuario.setNombre(usuario.getNombre());
            updateUsuario.setApellidoUno(usuario.getApellidoUno());
            updateUsuario.setApellidoDos(usuario.getApellidoDos());
            updateUsuario.setEmail(usuario.getEmail());
            updateUsuario.setTelefono(usuario.getTelefono());
            updateUsuario.setPassword(usuario.getPassword());
            updateUsuario.setRol(usuario.getRol());
            return usuarioRepository.save(updateUsuario);
        } else {
            throw new ResponseStatusException(HttpStatus.NOT_FOUND, "Usuario no encontrado");
        }
    }

    public Optional<Usuario> findByUserName(String userName) {
    return usuarioRepository.findByUserName(userName);
}

}
