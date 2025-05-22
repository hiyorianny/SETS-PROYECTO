import React, { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Cookies from "js-cookie";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import "./Login.css";
import logo from "../assets/img/c.png";

const Login = () => {
  const [formData, setFormData] = useState({
    Usuario: "",
    Clave: "",
  });
  const [mensaje, setMensaje] = useState("");
  const navigate = useNavigate();

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post(
      "http://localhost/SETS-PROYECTO/Backend/auth/login.php",
      formData,
      {
        headers: { 
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        withCredentials: true,
      }
    );

      console.log("Respuesta completa:", response.data); // Debug

      const { success, redirect, token, rol, rolDesc } = response.data;

      if (!success) {
        throw new Error(response.data.error || "Error al iniciar sesión");
      }

      if (token) {
        Cookies.set("token", token, { expires: 1 });
      }

      console.log(
        `Rol recibido: ${rol} (${rolDesc}), Redirección: ${redirect}`
      ); // Debug

      if (redirect) {
        const rutas = {
          1111: "http://localhost/SETS-PROYECTO/frontend-web/admin/inicioprincipal.php",
          2222: "http://localhost/SETS-PROYECTO/frontend-web/seguridad/inicioprincipal.php",
          3333: "http://localhost/SETS-PROYECTO/frontend-web/residente/inicioprincipal.php",
          error: "http://localhost/SETS-PROYECTO/frontend-web/error.php",
        };

        console.log("Intentando redirigir a:", rutas[redirect]); 

        toast.success("Inicio de sesión exitoso", {
          position: "top-right",
          autoClose: 2000,
          onClose: () => {
            window.location.href = rutas[redirect] || rutas.error;
          },
        });
      }
    } catch (error) {
      console.error("Error en login:", error.response || error); // Debug
      const mensajeError = error.response?.data?.error || error.message;
      setMensaje(mensajeError);
      toast.error(mensajeError, {
        position: "top-right",
        autoClose: 3000,
      });
    }
  };

  return (
    <div className="container">
      <ToastContainer /> {/* Contenedor para las notificaciones */}
      <header className="text-center mb-4 d-flex flex-column align-items-center">
        <img src={logo} alt="Logo" />
        <h2 className="title">
          SETS
          <br />
          BIENVENIDO
        </h2>
      </header>
      <h2>Iniciar Sesión</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          name="Usuario"
          placeholder="Usuario"
          value={formData.Usuario}
          onChange={handleChange}
          required
        />
        <input
          type="password"
          name="Clave"
          placeholder="Contraseña"
          value={formData.Clave}
          onChange={handleChange}
          required
        />
        <button type="submit">Iniciar Sesión</button>
      </form>
      {mensaje && <p className="error">{mensaje}</p>}
      <div className="d-flex justify-content-between">
        <a href="http://localhost:3000/registro">Registrarse</a>
        <a href="http://localhost:3000/recuperarcontrase%C3%B1a">
          Recuperar Contraseña
        </a>
        <a href="http://localhost/SETS-PROYECTO/frontend-web/">Volver</a>
      </div>
    </div>
  );
};

export default Login;
