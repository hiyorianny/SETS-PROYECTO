import React from "react";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Login from "./components/Login";
import Registro from "./components/Registro";
import RecuperarContraseña from "./components/recuperarcontraseña"; 
import Redirect from "./components/Redirect"; 
import CambiarContraseña from "./components/CambiarContraseña";


function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Redirect />} /> {/* Redirige a /SETS */}
        <Route path="/login" element={<Login />} />
        <Route path="/registro" element={<Registro />} />
        <Route path="/RecuperarContraseña" element={<RecuperarContraseña />} />
        <Route path="/cambiar-contrasena" element={<CambiarContraseña />} />
        
      </Routes>
    </Router>
  );
}

export default App;
