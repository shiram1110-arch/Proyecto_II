package proyectouno.api.entity;

import java.util.List;

import com.fasterxml.jackson.annotation.JsonIgnore;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "usuarios")
@Getter
@Setter
@ToString(exclude = { "reservas" })
@EqualsAndHashCode(exclude = { "reservas" })
@AllArgsConstructor
@NoArgsConstructor

public class Usuario {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idUsuario;
    @Column(name = "nombre", nullable = false)
    private String nombre;
    @Column(name = "apellidoUno", nullable = false)
    private String apellidoUno;
    @Column(name = "apellidoDos", nullable = false)
    private String apellidoDos;
    @Column(name = "email", nullable = false, unique = true)
    private String email;
    @Column(name = "telefono", nullable = false)
    private String telefono;
    @Column(name = "userName", nullable = false, unique = true)
    private String userName;
    @Column(name = "password", nullable = false)
    private String password;
    @ManyToOne
    @JoinColumn(name = "idRol", nullable = false)
    private Rol rol;
    @OneToMany(mappedBy = "usuario")
    @JsonIgnore
    private List<Reserva> reservas;
}
