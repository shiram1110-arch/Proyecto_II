package proyectouno.api.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
public class LoginController {

    @GetMapping("/login")
    public String login() {
        return "login";
    }

    @GetMapping("/registro")
    public String mostrarFormulario() {
        return "formularioVikingNuevo";
    }

    @PostMapping("/registro")
    public String procesarRegistro() {
        return "redirect:/crearUsuario";
    }

    @GetMapping("/crearUsuario")
    public String crearUsuario() {

        return "crearUsuario";

    }

}
