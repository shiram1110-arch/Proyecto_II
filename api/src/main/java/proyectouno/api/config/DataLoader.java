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
    CommandLineRunner initUsers(UsuarioRepository userRepository,
            RolRepository rolRepository,
            PasswordEncoder passwordEncoder) {

        return args -> {

            Rol userRole = rolRepository.findByNombre("ROLE_USER")
                    .orElseGet(() -> {
                        Rol r = new Rol();
                        r.setNombre("ROLE_USER");
                        return rolRepository.save(r);
                    });

            Rol adminRole = rolRepository.findByNombre("ROLE_ADMIN")
                    .orElseGet(() -> {
                        Rol r = new Rol();
                        r.setNombre("ROLE_ADMIN");
                        return rolRepository.save(r);
                    });

            if (userRepository.findByUserName("usuario").isEmpty()) {

                Usuario user = new Usuario();

                user.setNombre("Juan");
                user.setApellidoUno("Perez");
                user.setApellidoDos("Lopez");
                user.setEmail("usuario@gmail.com");
                user.setTelefono("88888888");
                user.setUserName("usuario");
                user.setPassword(passwordEncoder.encode("1234"));
                user.setRol(userRole);

                userRepository.save(user);
            }

            if (userRepository.findByUserName("admin").isEmpty()) {

                Usuario admin = new Usuario();

                admin.setNombre("Admin");
                admin.setApellidoUno("Principal");
                admin.setApellidoDos("Sistema");
                admin.setEmail("admin@gmail.com");
                admin.setTelefono("99999999");
                admin.setUserName("admin");
                admin.setPassword(passwordEncoder.encode("1234"));
                admin.setRol(adminRole);

                userRepository.save(admin);
            }
        };
    }
}