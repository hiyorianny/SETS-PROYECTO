import { useEffect } from "react";

const Redirect = () => {
  useEffect(() => {
    // Redirige a una URL completa
    window.location.replace("http://localhost/SETS-PROYECTO/frontend-web/");
  }, []);

  return null; 
};

export default Redirect;
