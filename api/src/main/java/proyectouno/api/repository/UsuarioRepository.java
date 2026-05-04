package proyectouno.api.repository;

import java.util.Optional;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Usuario;

public interface UsuarioRepository extends JpaRepository<Usuario, Integer> {
    Optional<Usuario> findByUserName(String userName);
}
