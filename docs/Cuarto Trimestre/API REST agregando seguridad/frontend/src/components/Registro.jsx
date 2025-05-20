import React, { useState, useEffect } from "react";
import axios from "axios";
import "./registro.css";
import logo from "../assets/img/c.png";
import Cookies from "js-cookie";
import { ToastContainer, toast } from "react-toastify"; 
import "react-toastify/dist/ReactToastify.css"; 

const Registro = () => {
  const [formData, setFormData] = useState({
    PrimerNombre: "",
    SegundoNombre: "",
    PrimerApellido: "",
    SegundoApellido: "",
    Correo: "",
    Usuario: "",
    Clave: "",
    Id_tipoDocumento: "",
    numeroDocumento: "",
    apartamento: "",
    telefonoUno: "",
    telefonoDos: "",
    idRol: "",
    tipo_propietario: "",
  });

  const [tipodocs, setTipodocs] = useState([]);
  const [roles, setRoles] = useState([]);
  const [mensaje, setMensaje] = useState("");
  const [errors, setErrors] = useState({});

  useEffect(() => {
    const fetchData = async () => {
      try {
        const rolResponse = await axios.get(
          "http://localhost/sets/backend/regi.php?tipo=roles"
        );
        setRoles(rolResponse.data);

        const tipoDocResponse = await axios.get(
          "http://localhost/sets/backend/regi.php?tipo=tipodocs"
        );
        setTipodocs(tipoDocResponse.data);
      } catch (error) {
        setMensaje("Error al cargar los datos.");
        console.error("Error al cargar los datos:", error);
      }
    };

    fetchData();
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });

    let errorMessage = "";

    switch (name) {
      case "PrimerNombre":
      case "SegundoNombre":
      case "PrimerApellido":
      case "SegundoApellido":
        if (!/^[a-zA-Z\s]*$/.test(value)) {
          errorMessage = "Solo se deben colocar letras.";
        }
        break;
      case "Correo":
        if (
          !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|org|net|edu|gov|co|mx|ar|cl|es)$/i.test(
            value
          )
        ) {
          errorMessage = "El correo debe ser válido y de un dominio conocido.";
        }
        break;
      case "numeroDocumento":
        if (!/^\d{10,}$/.test(value)) {
          errorMessage =
            "El número de documento debe tener al menos 10 dígitos.";
        }
        break;
      case "telefonoUno":
      case "telefonoDos":
        if (!/^\d{10}$/.test(value)) {
          errorMessage = "El teléfono debe tener exactamente 10 dígitos.";
        }
        break;
      case "idRol":
        if (!/^\d+$/.test(value)) errorMessage = "Solo se permiten números.";
        break;
      case "Clave":
        if (value.length < 8 || value.length > 17) {
          errorMessage = "La contraseña debe tener entre 8 y 17 caracteres.";
        }
        break;
      default:
        break;
    }

    setErrors({ ...errors, [name]: errorMessage });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Filtramos los errores para eliminar los de campos que no son requeridos
    const filteredErrors = {...errors};
    if (formData.idRol === "2222") {
      delete filteredErrors.tipo_propietario;
      delete filteredErrors.apartamento;
    }

    const hasErrors = Object.values(filteredErrors).some((error) => error);
    if (hasErrors) {
        setMensaje("Por favor corrige los errores antes de enviar.");
        return;
    }

    // Preparamos los datos a enviar
    const dataToSend = {...formData};
    if (formData.idRol === "2222") {
      dataToSend.tipo_propietario = "";
      dataToSend.apartamento = "";
    }

    try {
        const response = await axios.post(
            "http://localhost/sets/backend/regi.php",
            dataToSend,
            {
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                withCredentials: true,
            }
        );

        console.log(response.data); 

        const { redirect, token } = response.data;

        if (token) {
            Cookies.set("token", token, { expires: 1 });
        }

        if (redirect) {
            toast.success("Registro realizado correctamente", {
                position: "top-right",
                autoClose: 2000, 
                onClose: () => {
                    const rutas = {
                        1111: "http://localhost/sets/admin/BIENVENIDOADMI.php",
                        2222: "http://localhost/sets/seguridad/BIENVENIDOGUARDA.php",
                        3333: "http://localhost/sets/residente/BIENVENIDORESIDENTE.php",
                        4444: "http://localhost/sets/dueño/BIENVENIDORESIDENTE.php",
                        error: "http://localhost/SETS/error.html",
                    };
                    window.location.href = rutas[redirect] || rutas["error"];
                },
            });
        }
    } catch (error) {
        setMensaje(
            error.response?.data?.error || "Error al registrar el usuario."
        );
        toast.error("Error al registrar el usuario", {
            position: "top-right",
            autoClose: 3000,
        });
    }
  };

  return (
    <div className="container">
      <ToastContainer />
      <br /> <p />
      <p />
      <header className="text-center mb-4 d-flex flex-column align-items-center">
        <img src={logo} alt="Logo" className="logo" />
        <br />
        <h1 className="title">
          <b>
            SETS
            <br />
            BIENVENIDO
          </b>
        </h1>
      </header>
      <h2>Registro de Usuario</h2>
      <br />
      <h6>
        <b>Selecciona tu rol :</b>
      </h6>
      <form onSubmit={handleSubmit}>
        <select
          name="idRol"
          value={formData.idRol}
          onChange={handleChange}
          required
        >
          <option value="">Seleccionar Rol</option>
          {roles.map((role) => (
            <option key={role.id} value={role.id}>
              {role.Roldescripcion}
            </option>
          ))}
        </select>

        <label htmlFor="idRol">Ingresar el número del Rol:</label>
        <input
          type="number"
          id="idRol"
          name="idRol"
          placeholder="Ingresa el numero del Rol"
          required
          onChange={handleChange}
        />
        {errors.idRol && <p className="error">{errors.idRol}</p>}
        <h6>
          <b> Informacion Personal :</b>
        </h6>
   
        <input
          type="text"
          name="PrimerNombre"
          placeholder="Primer Nombre"
          value={formData.PrimerNombre}
          onChange={handleChange}
          required
        />
        {errors.PrimerNombre && <p className="error">{errors.PrimerNombre}</p>}
        <input
          type="text"
          name="SegundoNombre"
          placeholder="Segundo Nombre"
          value={formData.SegundoNombre}
          onChange={handleChange}
        />
        {errors.SegundoNombre && (
          <p className="error">{errors.SegundoNombre}</p>
        )}
        <input
          type="text"
          name="PrimerApellido"
          placeholder="Primer Apellido"
          value={formData.PrimerApellido}
          onChange={handleChange}
          required
        />
        {errors.PrimerApellido && (
          <p className="error">{errors.PrimerApellido}</p>
        )}

        <input
          type="text"
          name="SegundoApellido"
          placeholder="Segundo Apellido"
          value={formData.SegundoApellido}
          onChange={handleChange}
        />
        {errors.SegundoApellido && (
          <p className="error">{errors.SegundoApellido}</p>
        )}
        <input
          type="email"
          name="Correo"
          placeholder="Correo"
          value={formData.Correo}
          onChange={handleChange}
          required
        />
        {errors.Correo && <p className="error">{errors.Correo}</p>}

        <select
          name="Id_tipoDocumento"
          value={formData.Id_tipoDocumento}
          onChange={handleChange}
          required
        >
          <option value="">Seleccionar Tipo de Documento</option>
          {tipodocs.map((doc) => (
            <option key={doc.idtDoc} value={doc.idtDoc}>
              {doc.descripcionDoc}
            </option>
          ))}
        </select>

        <input
          type="number"
          name="numeroDocumento"
          placeholder="Número de Documento"
          value={formData.numeroDocumento}
          onChange={handleChange}
          required
        />
        {errors.numeroDocumento && (
          <p className="error">{errors.numeroDocumento}</p>
        )}

        {/* Campos condicionales para tipo_propietario y apartamento */}
        {formData.idRol !== "2222" && (
          <>
            <select
              name="tipo_propietario"
              value={formData.tipo_propietario}
              onChange={handleChange}
              required={formData.idRol !== "2222"}
            >
              <option value="">Seleccionar Tipo de Propietario</option>
              <option value="dueño">Dueño</option>
              <option value="residente">Residente</option>
              <option value="ambos">Ambos</option>
            </select>
            <input
              type="text"
              name="apartamento"
              placeholder="Número de apartamento"
              value={formData.apartamento}
              onChange={handleChange}
              required={formData.idRol !== "2222"}
            />
          </>
        )}

        <input
          type="number"
          name="telefonoUno"
          placeholder="Teléfono 1"
          value={formData.telefonoUno}
          onChange={handleChange}
          required
        />
        {errors.telefonoUno && <p className="error">{errors.telefonoUno}</p>}
        <input
          type="number"
          name="telefonoDos"
          placeholder="Teléfono 2"
          value={formData.telefonoDos}
          onChange={handleChange}
        />
        {errors.telefonoDos && <p className="error">{errors.telefonoDos}</p>}
        <h6>
          <b> Introduce tus credenciales:</b>
        </h6>

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
          placeholder="Clave"
          value={formData.Clave}
          onChange={handleChange}
          required
        />
        {errors.Clave && <p className="error">{errors.Clave}</p>}
        <button type="submit">Registrar</button>
      </form>
      <br />
      {mensaje && <p className="error">{mensaje}</p>}
      <div className="d-flex justify-content-between">
        <a href="http://localhost:3000/login" className="r">
          Iniciar Sesion
        </a>
        <a href="http://localhost:3000/recuperarcontrase%C3%B1a" className="e">
          Recuperar Contraseña
        </a>
        <a href="http://localhost/SETS/" className="r">
          Volver
        </a>
      </div>
    </div>
  );
};

export default Registro;