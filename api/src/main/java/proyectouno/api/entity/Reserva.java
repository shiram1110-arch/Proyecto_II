package proyectouno.api.entity;

import java.time.LocalDate;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "reservas")
@Getter
@Setter
@ToString(exclude = {"usuario", "clase"})
@EqualsAndHashCode(exclude = {"usuario", "clase"})

public class Reserva {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idReserva;
    @ManyToOne
    @JsonIgnoreProperties("reservas")
    @JoinColumn(name = "idUsuario", nullable = false)
    private Usuario usuario;
    @ManyToOne
    @JsonIgnoreProperties("reservas")
    @JoinColumn(name = "idClase", nullable = false)
    private Clase clase;
    @Column(name = "fechaReserva", nullable = false)
    private LocalDate fechaReserva;
    @Column(name = "estado", nullable = false)
    private String estado;

}
