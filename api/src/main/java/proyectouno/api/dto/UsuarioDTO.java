package proyectouno.api.dto;

public class UsuarioDTO {
    public String nombre;
    public String apellidoUno;
    public String userName;
    
    public UsuarioDTO() {
    }
    public String getNombre() {
        return nombre;
    }
    public void setNombre(String nombre) {
        this.nombre = nombre;
    }
    public String getApellidoUno() {
        return apellidoUno;
    }
    public void setApellidoUno(String apellidoUno) {
        this.apellidoUno = apellidoUno;
    }
    public String getUserName() {
        return userName;
    }
    public void setUserName(String userName) {
        this.userName = userName;
    }

    
}
