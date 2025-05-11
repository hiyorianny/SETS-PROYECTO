import React, { useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import "./Login.css";
import logo from "../assets/img/c.png";

const CambiarContraseña = () => {
  const [nuevaContraseña, setNuevaContraseña] = useState("");
  const [confirmarContraseña, setConfirmarContraseña] = useState("");
  const [mensaje, setMensaje] = useState("");
  const [errorContraseña, setErrorContraseña] = useState("");
  const [cargando, setCargando] = useState(false);
  const navigate = useNavigate();
  const location = useLocation();
  const token = new URLSearchParams(location.search).get("token");


  const validarContraseña = (contraseña) => {
    if (contraseña.length < 8 || contraseña.length > 10) {
      setErrorContraseña("La contraseña debe tener entre 8 y 10 caracteres.");
      return false;
    } else {
      setErrorContraseña("");
      return true;
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setCargando(true);
    setMensaje("");

    // Validar la contraseña antes de enviar
    if (!validarContraseña(nuevaContraseña)) {
      setCargando(false);
      return;
    }

    if (nuevaContraseña !== confirmarContraseña) {
      setMensaje("Las contraseñas no coinciden.");
      setCargando(false);
      return;
    }

    try {
      const response = await fetch(
        "http://localhost/sets/backend/cambiarcontrasena.php",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ token, nuevaContraseña }),
        }
      );

      const data = await response.json();

      if (response.ok) {
        setMensaje(data.mensaje);
        setTimeout(() => {
          navigate("/login");
        }, 2000);
      } else {
        setMensaje(data.error || "Error al cambiar la contraseña.");
      }
    } catch (error) {
      setMensaje("Error de conexión. Inténtalo de nuevo más tarde.");
    } finally {
      setCargando(false);
    }
  };

  return (
    <main>
      <section className="container">
        <article className="login-content">
          <form onSubmit={handleSubmit}>
            <figure>
              <img src={logo} alt="Logo" />
            </figure>
            <h2 className="title">
              <b>Cambiar Contraseña</b>
            </h2>
            <div className="input-div one">
              <div className="div">
                <input
                  type="password"
                  className="input"
                  name="nuevaContraseña"
                  required
                  placeholder="Nueva Contraseña"
                  value={nuevaContraseña}
                  onChange={(e) => {
                    setNuevaContraseña(e.target.value);
                    validarContraseña(e.target.value); 
                  }}
                />
              </div>
              {errorContraseña && <p className="error">{errorContraseña}</p>}
            </div>
            <div className="input-div one">
              <div className="div">
                <input
                  type="password"
                  className="input"
                  name="confirmarContraseña"
                  required
                  placeholder="Confirmar Contraseña"
                  value={confirmarContraseña}
                  onChange={(e) => setConfirmarContraseña(e.target.value)}
                />
              </div>
            </div>
            <input
              type="submit"
              className="btn btn-success btn-lg"
              value={cargando ? "Cambiando..." : "Cambiar Contraseña"}
              disabled={cargando || errorContraseña || nuevaContraseña.length < 8 || nuevaContraseña.length > 10}
            />
            {mensaje && <p className="mensaje">{mensaje}</p>}
            <div className="d-flex justify-content-between">
              <a
                href="http://localhost:3000/recuperarcontrase%C3%B1a"
                className="r"
              >
                Volver
              </a>
            </div>
          </form>
        </article>
      </section>
    </main>
  );
};

export default CambiarContraseña;