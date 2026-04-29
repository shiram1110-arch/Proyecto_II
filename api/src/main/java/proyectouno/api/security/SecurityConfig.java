package proyectouno.api.security;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.http.HttpMethod;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.authentication.UsernamePasswordAuthenticationFilter;

@Configuration
public class SecurityConfig {

        private final JwtRequestFilter jwtRequestFilter;

        public SecurityConfig(JwtRequestFilter jwtRequestFilter) {
                this.jwtRequestFilter = jwtRequestFilter;
        }

        @Bean
        public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
                return http
                                // 🔥 IMPORTANTE: desactiva CSRF para usar fetch desde HTML
                                .csrf(csrf -> csrf.disable())

                                .authorizeHttpRequests(auth -> auth

                                                // 🔓 LOGIN API (JWT)
                                                .requestMatchers("/registroCompleto/login").permitAll()

                                                // 🔓 REGISTRO (🔥 CLAVE PARA TU ERROR 403)
                                                .requestMatchers(HttpMethod.POST, "/usuarios").permitAll()

                                                // 🔓 VISTAS Y RECURSOS
                                                .requestMatchers("/", "/inicio", "/login",
                                                                "/registro", "/formularioVikingNuevo",
                                                                "/usuariosVista", "/clasesVista",
                                                                "/adminDashboard", "/gestionReservas",
                                                                "/horarioClases", "/reservas/**",
                                                                "/crearClase", "/crearUsuario",
                                                                "/clases/**", "/error",
                                                                "/img/**", "/css/**", "/js/**",
                                                                "/swagger-ui/**", "/v3/api-docs/**")
                                                .permitAll()

                                                // 🔒 TODO LO DEMÁS REQUIERE LOGIN
                                                .anyRequest().authenticated())

                                // 🔐 FILTRO JWT
                                .addFilterBefore(jwtRequestFilter, UsernamePasswordAuthenticationFilter.class)

                                .build();
        }

        @Bean
        public AuthenticationManager authenticationManager(AuthenticationConfiguration config) throws Exception {
                return config.getAuthenticationManager();
        }

        @Bean
        public PasswordEncoder passwordEncoder() {
                return new BCryptPasswordEncoder();
        }
}