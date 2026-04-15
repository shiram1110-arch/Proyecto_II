package proyectouno.api.entity;

import java.util.List;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Table(name = "roles")
@Data

public class Rol {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idRol;
    @Column(name = "nombre", nullable = false, unique = true)
    private String nombre;
    @OneToMany(mappedBy = "rol")
    private List<Usuario> usuarios;
}
