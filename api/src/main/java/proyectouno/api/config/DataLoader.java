package proyectouno.api.config;

import org.springframework.boot.CommandLineRunner;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.crypto.password.PasswordEncoder;

import proyectouno.api.entity.Usuario;
import proyectouno.api.entity.Rol;
import proyectouno.api.repository.UsuarioRepository;
import proyectouno.api.repository.RolRepository;

@Configuration
public class DataLoader {

    @Bean
    CommandLineRunner initUsers(UsuarioRepository userRepository,RolRepository rolRepository, 
        PasswordEncoder passwordEncoder) {

        return args -> {
System.out.println("Iniciando DataLoader...");
            Rol rol = rolRepository.findById(2)
                    .orElseThrow(() -> new RuntimeException("Rol no encontrado"));

            if (userRepository.findByUserName("Runor").isEmpty()) {

                Usuario u = new Usuario();

                u.setNombre("Alex");
                u.setApellidoUno("Morales");
                u.setApellidoDos("Picado");
                u.setEmail("adpm@gmail.com");
                u.setTelefono("84999890");
                u.setUserName("Runor");

                u.setPassword(passwordEncoder.encode("1234"));

                u.setRol(rol);

                userRepository.save(u);

                System.out.println("ENTREEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE..");

            }
        };
    }
}