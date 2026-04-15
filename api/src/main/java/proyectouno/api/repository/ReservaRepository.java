package proyectouno.api.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Reserva;

public interface ReservaRepository extends JpaRepository<Reserva, Integer>{
    
}
