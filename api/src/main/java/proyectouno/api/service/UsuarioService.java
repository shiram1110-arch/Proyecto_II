package proyectouno.api.service;

import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.*;
import proyectouno.api.entity.Usuario;
import proyectouno.api.repository.UsuarioRepository;

@Service
@AllArgsConstructor
public class UsuarioService {
    private UsuarioRepository usuarioRepository;

    public Usuario add(Usuario usuario) {
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

}
