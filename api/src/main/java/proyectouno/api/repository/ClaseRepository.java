package proyectouno.api.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import proyectouno.api.entity.Clase;

import java.time.LocalTime;
import java.util.List;

public interface ClaseRepository extends JpaRepository<Clase, Integer> {

    List<Clase> findByDiaSemanaOrderByHorarioAsc(String diaSemana);

    List<Clase> findByNombreContainingIgnoreCase(String nombre);

    boolean existsByDiaSemanaAndHorario(String diaSemana, LocalTime horario);

}
