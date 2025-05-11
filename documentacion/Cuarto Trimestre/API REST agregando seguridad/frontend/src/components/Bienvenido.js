import React from "react";
import { useNavigate } from "react-router-dom";

function Bienvenido() {
  const navigate = useNavigate();

  return (
    <div style={{ textAlign: "center", marginTop: "20%" }}>
      <h1>Bienvenido</h1>
      <p>Por favor, selecciona una opción:</p>
      <button
        onClick={() => navigate("/login")}
        style={{
          margin: "10px",
          padding: "10px 20px",
          backgroundColor: "#007BFF",
          color: "white",
          border: "none",
          borderRadius: "5px",
          cursor: "pointer",
        }}
      >
        Ir a Login
      </button>
      <button
        onClick={() => navigate("/registro")}
        style={{
          margin: "10px",
          padding: "10px 20px",
          backgroundColor: "#28A745",
          color: "white",
          border: "none",
          borderRadius: "5px",
          cursor: "pointer",
        }}
      >
        Ir a Registro
      </button>
    </div>
  );
}

export default Bienvenido;
