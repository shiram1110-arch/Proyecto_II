package proyectouno.api.entity;

import java.time.LocalDate;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "reservas")
@Data

public class Reserva {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idReserva;
    @ManyToOne
    @JoinColumn(name = "idUsuario", nullable = false)
    private Usuario usuario;
    @ManyToOne
    @JoinColumn(name = "idClase", nullable = false)
    private Clase clase;
    @Column(name = "fechaReserva", nullable = false)
    private LocalDate fechaReserva;
    @Column(name = "estado", nullable = false)
    private String estado;

}
