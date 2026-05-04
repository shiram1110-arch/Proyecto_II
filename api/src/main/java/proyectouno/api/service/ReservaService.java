package proyectouno.api.service;

import java.time.LocalDate;
import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.dto.ReservaDTO;
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

        if (reserva.getUsuario() == null) {
            throw new ResponseStatusException(HttpStatus.BAD_REQUEST, "Usuario requerido");
        }

        // 🔴 VALIDAR QUE NO EXISTA YA LA RESERVA (NUEVO)
        boolean existe = !reservaRepository
                .findByUsuario_IdUsuarioAndClase_IdClase(
                        reserva.getUsuario().getIdUsuario(),
                        reserva.getClase().getIdClase())
                .isEmpty();

        if (existe) {
            throw new ResponseStatusException(
                    HttpStatus.BAD_REQUEST,
                    "Ya tienes una reserva para esta clase");
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

    public List<ReservaDTO> getReservasPorUsuario(int id) {

        List<Reserva> reservas = reservaRepository.findByUsuario_IdUsuario(id);

        return reservas.stream().map(r -> {
            ReservaDTO dto = new ReservaDTO();
            dto.setNombreClase(r.getClase().getNombre());
            dto.setCapacidad(r.getClase().getCapacidad());
            dto.setFechaReserva(r.getFechaReserva());
            dto.setHorario(r.getClase().getHorario());
            dto.setEstado(r.getEstado());
            return dto;
        }).toList();
    }

    public List<Reserva> getByEstado(String estado){
    return reservaRepository.findByEstadoIgnoreCase(estado);
}

}