import React, { useState } from "react";
import "./Login.css";
import logo from "../assets/img/c.png";
import { useNavigate } from "react-router-dom";

const RecuperarContraseña = () => {
  const [correo, setCorreo] = useState("");
  const [mensaje, setMensaje] = useState("");
  const [error, setError] = useState("");
  const [cargando, setCargando] = useState(false);
  const navigate = useNavigate();

  const validarEmail = (email) => {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setCargando(true);
    setMensaje("");
    setError("");

 
    if (!correo) {
      setError("El correo es obligatorio");
      setCargando(false);
      return;
    }

    if (!validarEmail(correo)) {
      setError("Por favor ingresa un correo válido");
      setCargando(false);
      return;
    }

    try {
      const response = await fetch("http://localhost/sets/MODEL/backend/recuperarcontrsena.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ correo }),
      });

      const data = await response.json();

      if (response.ok) {
        setMensaje(data.mensaje);
    
      } else {
        setError(data.error || "Error al procesar la solicitud.");
      }
    } catch (err) {
      setError("Error de conexión. Inténtalo de nuevo más tarde.");
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
            <h2 className="title"><b>Recuperar Contraseña</b></h2>
            
            {error && <div className="alert alert-danger">{error}</div>}
            {mensaje && <div className="alert alert-success">{mensaje}</div>}

            <div className="input-div one">
              <div className="div">
                <input
                  type="email"
                  className="input"
                  name="correo"
                  required
                  placeholder="Correo electrónico"
                  value={correo}
                  onChange={(e) => setCorreo(e.target.value)}
                  disabled={cargando}
                />
              </div>
            </div>
            
            <button
              type="submit"
              className="btn btn-success btn-lg"
              disabled={cargando}
            >
              {cargando ? (
                <>
                  <span className="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  <span className="sr-only">Enviando...</span>
                </>
              ) : (
                "Enviar enlace de recuperación"
              )}
            </button>
            
            <div className="d-flex justify-content-between mt-3">
              <a href="http://localhost:3000/login" className="text-decoration-none">
                Iniciar Sesión
              </a>
              <a href="http://localhost:3000/registro" className="text-decoration-none">
                Registrarse
              </a>
              <a href="http://localhost/sets/VIEW/" className="text-decoration-none">
                Volver al inicio
              </a>
            </div>
          </form>
        </article>
      </section>
    </main>
  );
};

export default RecuperarContraseña;