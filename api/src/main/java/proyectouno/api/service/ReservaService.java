package proyectouno.api.service;

import java.time.LocalDate;
import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.dto.ReservaDTO;
import proyectouno.api.entity.Clase;
import proyectouno.api.entity.Reserva;
import proyectouno.api.repository.ReservaRepository;

@Service
@AllArgsConstructor
public class ReservaService {

    private ReservaRepository reservaRepository;
    private ClaseService claseService;

    // 🔥 CREATE
    public Reserva add(Reserva reserva) {

        validarDatosReserva(reserva);
        validarReservaDuplicada(reserva);

        Clase clase = reserva.getClase();

        // 🔥 VALIDAR CUPOS
        if (clase.getCapacidad() <= 0) {
            throw new ResponseStatusException(
                    HttpStatus.BAD_REQUEST,
                    "No hay cupos disponibles");
        }

        // 🔥 RESTAR CUPO
        clase.setCapacidad(clase.getCapacidad() - 1);

        // 🔥 GUARDAR CAMBIO EN CLASE
        claseService.save(clase);

        completarDatosPorDefecto(reserva);

        return reservaRepository.save(reserva);
    }

    // 📄 GET ALL
    public List<Reserva> get() {
        return reservaRepository.findAll();
    }

    // 🔍 GET BY ID
    public Optional<Reserva> getById(int id) {
        return reservaRepository.findById(id);
    }

    // ❌ DELETE
    public void delete(int id) {

        Reserva reserva = obtenerReservaPorId(id);

        Clase clase = reserva.getClase();

        // 🔥 DEVOLVER CUPO
        clase.setCapacidad(clase.getCapacidad() + 1);
        claseService.save(clase);

        reservaRepository.deleteById(id);
    }

    // 🔄 UPDATE
    public Reserva update(int id, Reserva reserva) {

        Reserva existing = obtenerReservaPorId(id);
        actualizarDatosReserva(existing, reserva);

        return reservaRepository.save(existing);
    }

    // 🔍 FILTRAR POR ESTADO (DTO)
    public List<ReservaDTO> getByEstadoDTO(String estado) {
        return reservaRepository.findByEstadoIgnoreCase(estado)
                .stream()
                .map(this::convertirADTO)
                .toList();
    }

    // =========================
    // 🧩 MÉTODOS PRIVADOS
    // =========================

    private void validarDatosReserva(Reserva reserva) {
        if (reserva.getClase() == null) {
            throw new ResponseStatusException(HttpStatus.BAD_REQUEST, "Clase requerida");
        }

        if (reserva.getUsuario() == null) {
            throw new ResponseStatusException(HttpStatus.BAD_REQUEST, "Usuario requerido");
        }
    }

    private void validarReservaDuplicada(Reserva reserva) {

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
    }

    private void completarDatosPorDefecto(Reserva reserva) {

        if (reserva.getFechaReserva() == null) {
            reserva.setFechaReserva(LocalDate.now());
        }

        if (reserva.getEstado() == null) {
            reserva.setEstado("ACTIVA");
        }
    }

    private Reserva obtenerReservaPorId(int id) {
        return reservaRepository.findById(id)
                .orElseThrow(() -> new ResponseStatusException(
                        HttpStatus.NOT_FOUND, "Reserva no encontrada"));
    }

    private void actualizarDatosReserva(Reserva existing, Reserva nueva) {
        existing.setClase(nueva.getClase());
        existing.setFechaReserva(nueva.getFechaReserva());
        existing.setEstado(nueva.getEstado());
    }

    private ReservaDTO convertirADTO(Reserva r) {

        ReservaDTO dto = new ReservaDTO();

        dto.setIdReserva(r.getIdReserva());
        dto.setNombreUsuario(r.getUsuario().getNombre());
        dto.setNombreClase(r.getClase().getNombre());
        dto.setCapacidad(r.getClase().getCapacidad());
        dto.setFechaReserva(r.getFechaReserva());
        dto.setHorario(r.getClase().getHorario());
        dto.setEstado(r.getEstado());

        return dto;
    }

    public List<ReservaDTO> getReservasPorUsuario(int id) {

        return reservaRepository.findByUsuario_IdUsuario(id)
                .stream()
                .map(this::convertirADTO)
                .toList();
    }

    public Reserva cancelarReserva(int id) {

        Reserva reserva = obtenerReservaPorId(id);

        if (reserva.getEstado().equalsIgnoreCase("CANCELADA")) {
            throw new ResponseStatusException(
                    HttpStatus.BAD_REQUEST,
                    "La reserva ya está cancelada");
        }

        // 🔥 cambiar estado
        reserva.setEstado("CANCELADA");

        // 🔥 devolver cupo
        Clase clase = reserva.getClase();
        clase.setCapacidad(clase.getCapacidad() + 1);
        claseService.save(clase);

        return reservaRepository.save(reserva);
    }
}