package proyectouno.api.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Reserva;

public interface ReservaRepository extends JpaRepository<Reserva, Integer> {

    List<Reserva> findByUsuario_IdUsuario(int idUsuario);
    List<Reserva> findByUsuario_IdUsuarioAndClase_IdClase(Integer idUsuario,Integer idClase);
    
}
