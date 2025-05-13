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
                "http://localhost/sets/backend/login.php",
                formData,
                {
                    headers: { "Content-Type": "application/json" },
                    withCredentials: true,
                }
            );

            const { redirect, token } = response.data;

            if (token) {
                Cookies.set("token", token, { expires: 1 }); 
            }

            if (redirect) {
                toast.success("Inicio de sesión exitoso", {
                    position: "top-right",
                    autoClose: 2000, 
                    onClose: () => {
                        const rutas = {
                            1111: "http://localhost/sets/admin/inicioprincipal.php",
                            2222: "http://localhost/sets/seguridad/inicioprincipal.php",
                            3333: "http://localhost/sets/residente/inicioprincipal.php",
                            4444: "http://localhost/sets/gestor_inmobiliaria/inicioprincipal.php",
                            error: "http://localhost/SETS/error.html",
                        };
                   
                        window.location.href = rutas[redirect] || rutas["error"];
                    },
                });
            }
        } catch (error) {
            setMensaje(error.response?.data?.error || "Error al iniciar sesión.");
            toast.error("Error al iniciar sesión", {
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
                    SETS<br />BIENVENIDO
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
                <a href="http://localhost:3000/recuperarcontrase%C3%B1a">Recuperar Contraseña</a>
                <a href="http://localhost/SETS/">Volver</a>
            </div>
        </div>
    );
};

export default Login;