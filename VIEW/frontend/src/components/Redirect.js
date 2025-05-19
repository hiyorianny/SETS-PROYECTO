// Redirect.js
import { useEffect } from "react";

const Redirect = () => {
  useEffect(() => {
    // Redirige a una URL completa
    window.location.replace("http://localhost/SETS/VIEW/");
  }, []);

  return null; // Este componente no renderiza nada
};

export default Redirect;
