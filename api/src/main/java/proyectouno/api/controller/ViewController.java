package proyectouno.api.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;


@Controller
public class ViewController {

    @GetMapping("/")
    public String inicio() {
        return "redirect:/inicio";
    }
    
    @GetMapping("/inicio")
    public String inicioVista() {
        return "inicio";
    }

    @GetMapping("horarioClases")
    public String horarioClases() {
        return "horarioClases";
    }

    @GetMapping("/reservas")
    public String reservas() {
        return "reservas"; 
    }

}
