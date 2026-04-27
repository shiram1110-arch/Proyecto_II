package proyectouno.api.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;

@Controller
public class LoginViewController {

    @GetMapping("/login")
    public String login() {
        return "login"; 
    }

    @GetMapping("/registro")
    public String registro() {
        return "formularioVikingNuevo"; 
    }

    
}
