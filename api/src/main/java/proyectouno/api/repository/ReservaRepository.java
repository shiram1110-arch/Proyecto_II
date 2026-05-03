package proyectouno.api.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Reserva;

public interface ReservaRepository extends JpaRepository<Reserva, Integer> {

    List<Reserva> findByUsuarioIdUsuario(int idUsuario);
    
}
