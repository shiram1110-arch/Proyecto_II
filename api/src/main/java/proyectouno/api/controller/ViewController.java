package proyectouno.api.controller;

import java.util.Optional;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;

import proyectouno.api.entity.Clase;
import proyectouno.api.service.ClaseService;

@Controller
public class ViewController {

    private final ClaseService claseService;

    public ViewController(ClaseService claseService) {
        this.claseService = claseService;
    }

    @GetMapping("/")
    public String inicio() {
        return "redirect:/inicio";
    }

    @GetMapping("/inicio")
    public String inicioVista() {
        return "inicio";
    }

    @GetMapping("/horarioClases")
    public String horarioClases(Model model, String diaSemana) {

        if (diaSemana == null || diaSemana.isEmpty()) {
            diaSemana = "LUNES";
        }

        model.addAttribute("clases", claseService.getClaseByDiaSemana(diaSemana));
        model.addAttribute("diaActual", diaSemana);

        return "horarioClases";
    }

    @GetMapping("/reservas/{idClase}")
    public String reservas(@PathVariable int idClase, Model model) {

        Optional<Clase> clase = claseService.getById(idClase);

        if (clase.isEmpty()) {
            return "redirect:/horarioClases";
        }

        model.addAttribute("clase", clase.get());

        return "reservas";
    }

    @GetMapping("/crearClase")
    public String crearClaseVista() {
        return "crearClase";
    }

    

}