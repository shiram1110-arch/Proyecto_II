package proyectouno.api.entity;

import java.time.LocalTime;
import java.util.List;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name="clases")
@Data

public class Clase {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idClase;
    @Column(name = "nombre", nullable = false)
    private String nombre;
    @Column(name = "descripcion", nullable = false)
    private String descripcion;
    @Column(name = "diaSemana", nullable = false)
    private String diaSemana;
    @Column(name = "horario", nullable = false)
    private LocalTime horario;
    @Column(name = "capacidad", nullable = false)
    private Integer capacidad;
    @OneToMany(mappedBy="clase")
    public List<Reserva> reservas;
}
