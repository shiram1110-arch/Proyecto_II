package proyectouno.api.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Usuario;

public interface UsuarioRepository extends JpaRepository<Usuario, Integer>{
    
}
