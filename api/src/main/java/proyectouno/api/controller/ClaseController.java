package proyectouno.api.controller;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.server.ResponseStatusException;

import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.tags.Tag;
import proyectouno.api.entity.Clase;
import proyectouno.api.service.ClaseService;

@CrossOrigin(origins = "*") // Permitir acceso desde cualquier origen
@Tag(name = "Clases", description = "API para gestionar clases")
// Grupo en Swagger
@RestController
@RequestMapping("/clases")

public class ClaseController {
    @Autowired
    private ClaseService claseService;

    @GetMapping
    @Operation(summary = "Obtener todas las clases", description = "Devuelve una lista de clases")
    public List<Clase> get() {
        return claseService.get();
    }

    @GetMapping("/clases/{diaSemana}")
    @Operation(summary = "Obtener todas las clases según el día", description = "Devuelve una lista de clases según el dia")
    public List<Clase> getClasesPorDia(@PathVariable String diaSemana){
        return claseService.getClaseByDiaSemana(diaSemana);
    }

    @GetMapping("/{id}")
    @Operation(summary = "Obtener una clase por ID", description = "Busca una clase en la base de datos según su ID")

    public Clase getById(@PathVariable int id) {
        return claseService.getById(id).orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND));
    }

    @PostMapping
    @Operation(summary = "Crear una nueva clase", description = "Agrega una nueva clase a la base de datos")

    public Clase add(@RequestBody Clase clase) {
        return claseService.add(clase);
    }

    @Operation(summary = "Modificar una clase", description = "Modifica una  clase existente en la base de datos")
    @PutMapping("/{id}")
    public Clase update(@PathVariable int id, @RequestBody Clase clase) {
        return claseService.update(id, clase);
    }

    @Operation(summary = "Eliminar una clase", description = "Elimina una clase de la base de datos")
    @DeleteMapping("/{id}")
    public void delete(@PathVariable int id) {
        claseService.delete(id);
    }

}
