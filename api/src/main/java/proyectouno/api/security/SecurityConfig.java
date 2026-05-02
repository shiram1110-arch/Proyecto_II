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

                                                // 🔓 LOGIN
                                                .requestMatchers("/registroCompleto/login").permitAll()

                                                // 🔓 REGISTRO USUARIO
                                                .requestMatchers(HttpMethod.POST, "/usuarios").permitAll()

                                                // 🔒 API USUARIOS (SOLO ADMIN)
                                                .requestMatchers(HttpMethod.GET, "/usuarios").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.PUT, "/usuarios/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.DELETE, "/usuarios/**").hasRole("ADMIN")

                                                // 🔥 IMPORTANTE: PERMITIR VISTA EDITAR
                                                .requestMatchers("/usuarios/editar/**").permitAll()

                                                // 🔓 CLASES públicas
                                                .requestMatchers(HttpMethod.GET, "/clases/**").permitAll()

                                                // 🔒 CLASES ADMIN
                                                .requestMatchers(HttpMethod.POST, "/clases/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.PUT, "/clases/**").hasRole("ADMIN")
                                                .requestMatchers(HttpMethod.DELETE, "/clases/**").hasRole("ADMIN")

                                                // 🔒 RESERVAS ADMIN
                                                .requestMatchers(HttpMethod.DELETE, "/api/reservas/**").hasRole("ADMIN")

                                                // 🔓 VISTAS (HTML)
                                                .requestMatchers(
                                                                "/", "/inicio", "/login", "/registro",
                                                                "/formularioVikingNuevo",
                                                                "/usuariosVista", "/clasesVista",
                                                                "/adminDashboard", "/gestionReservas",
                                                                "/horarioClases", "/reservas/**",
                                                                "/crearClase", "/crearUsuario",
                                                                "/error",
                                                                "/img/**", "/css/**", "/js/**",
                                                                "/swagger-ui/**", "/v3/api-docs/**")
                                                .permitAll()

                                                .anyRequest().authenticated())

                                // 🔐 FILTRO JWT
                                .addFilterBefore(jwtRequestFilter, UsernamePasswordAuthenticationFilter.class)

                                .build();
        }

        // 🔑 Authentication Manager
        @Bean
        public AuthenticationManager authenticationManager(AuthenticationConfiguration config) throws Exception {
                return config.getAuthenticationManager();
        }

        // 🔒 Password encoder
        @Bean
        public PasswordEncoder passwordEncoder() {
                return new BCryptPasswordEncoder();
        }
}