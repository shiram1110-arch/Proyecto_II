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

                                .csrf(csrf -> csrf.disable())

                                .authorizeHttpRequests(auth -> auth

                                                .requestMatchers("/registroCompleto/login").permitAll()

                                                .requestMatchers(HttpMethod.POST, "/usuarios").permitAll()

                                                .requestMatchers(HttpMethod.GET, "/usuarios").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.PUT, "/usuarios/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.DELETE, "/usuarios/**").hasRole("ADMIN")

                                                .requestMatchers("/usuarios/editar/**").permitAll()

                                                .requestMatchers(HttpMethod.GET, "/clases/**").permitAll()
                                                .requestMatchers("/api/reservas/mis-clases").permitAll()

                                                .requestMatchers(HttpMethod.POST, "/clases/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.PUT, "/clases/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.DELETE, "/clases/**").hasRole("ADMIN")

                                                .requestMatchers(HttpMethod.DELETE, "/api/reservas/**").hasRole("ADMIN")

                                                .requestMatchers(
                                                                "/", "/inicio", "/login", "/registro",
                                                                "/formularioVikingNuevo",
                                                                "/usuariosVista", "/clasesVista", "/historial",
                                                                "/adminDashboard", "/gestionReservas",
                                                                "/horarioClases", "/reservas/**",
                                                                "/crearClase", "/crearUsuario",
                                                                "/error",
                                                                "/img/**", "/css/**", "/js/**",
                                                                "/swagger-ui/**", "/v3/api-docs/**")
                                                .permitAll()

                                                .anyRequest().authenticated())

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