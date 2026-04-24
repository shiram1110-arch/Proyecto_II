package proyectouno.api.service;

import java.util.*;

import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.web.server.ResponseStatusException;

import lombok.AllArgsConstructor;
import proyectouno.api.entity.Clase;
import proyectouno.api.repository.ClaseRepository;

@Service
@AllArgsConstructor
public class ClaseService {
    private ClaseRepository claseRepository;

    public Clase add(Clase clase) {
        return claseRepository.save(clase);
    }

    public List<Clase> get() {
        return claseRepository.findAll();
    }

    public Optional<Clase> getById(int id) {
        return claseRepository.findById(id);
    }

    public void delete(int id) {
        claseRepository.deleteById(id);
    }

    public Clase update(int id, Clase clase) {
        Optional<Clase> existingClase = claseRepository.findById(id);
        if (existingClase.isPresent()) {
            Clase updateClase = existingClase.get();
            updateClase.setNombre(clase.getNombre());
            updateClase.setDescripcion(clase.getDescripcion());
            updateClase.setDiaSemana(clase.getDiaSemana());
            updateClase.setHorario(clase.getHorario());
            updateClase.setCapacidad(clase.getCapacidad());
            return claseRepository.save(updateClase);
        } else {
           throw new ResponseStatusException(HttpStatus.NOT_FOUND, "Clase no encontrada");
        }
    }

    public List<Clase> findByDiaSemana(String diaSemana){

        List<Clase> listaPorDia = new ArrayList<>();
    
        for (Clase c : claseRepository.findAll()) {
        if (c.getDiaSemana().equalsIgnoreCase(diaSemana)) {
            listaPorDia.add(c);
        }
    }
    return listaPorDia;
    }

}
