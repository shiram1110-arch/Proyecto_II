package proyectouno.api.service;

import java.time.LocalDate;
import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.entity.*;
import proyectouno.api.repository.ReservaRepository;

@Service
@AllArgsConstructor
public class ReservaService {

    private ReservaRepository reservaRepository;

    // 🔥 ADD (MEJORADO)
    public Reserva add(Reserva reserva) {

        // ⚠️ validar clase
        if (reserva.getClase() == null) {
            throw new ResponseStatusException(HttpStatus.BAD_REQUEST, "Clase requerida");
        }

        // 🔥 completar datos SI vienen nulos
        if (reserva.getFechaReserva() == null) {
            reserva.setFechaReserva(LocalDate.now());
        }

        if (reserva.getEstado() == null) {
            reserva.setEstado("ACTIVA");
        }

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

    // 🔒 UPDATE SEGURO
    public Reserva update(int id, Reserva reserva) {

        Reserva existing = reservaRepository.findById(id)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Reserva no encontrada"));

        // ❌ NO tocar usuario
        // existing.setUsuario(reserva.getUsuario());

        existing.setClase(reserva.getClase());
        existing.setFechaReserva(reserva.getFechaReserva());
        existing.setEstado(reserva.getEstado());

        return reservaRepository.save(existing);
    }
}