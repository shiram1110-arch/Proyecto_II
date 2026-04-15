package proyectouno.api.service;

import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.entity.Reserva;
import proyectouno.api.repository.ReservaRepository;

@Service
@AllArgsConstructor
public class ReservaService {
    private ReservaRepository reservaRepository;

    public Reserva add(Reserva reserva) {
        return reservaRepository.save(reserva);
    }

    public List<Reserva> get() {
        return reservaRepository.findAll();
    }

    public Optional<Reserva> getById(int id) {
        return reservaRepository.findById(id);
    }
    public void delete(int id) {
        reservaRepository.deleteById(id);
    }

    public Reserva update(int id, Reserva reserva) {
        Optional<Reserva> existingReserva = reservaRepository.findById(id);
        if (existingReserva.isPresent()) {
            Reserva updateReserva = existingReserva.get();
            updateReserva.setUsuario(reserva.getUsuario());
            updateReserva.setClase(reserva.getClase());
            updateReserva.setFechaReserva(reserva.getFechaReserva());
            updateReserva.setEstado(reserva.getEstado());
            return reservaRepository.save(updateReserva);
        } else {
           throw new ResponseStatusException(HttpStatus.NOT_FOUND, "Reserva no encontrada");
        }
    }


    
}
