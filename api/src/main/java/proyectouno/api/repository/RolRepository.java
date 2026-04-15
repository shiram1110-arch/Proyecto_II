package proyectouno.api.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Rol;

public interface RolRepository extends JpaRepository<Rol, Integer>{
    
}
