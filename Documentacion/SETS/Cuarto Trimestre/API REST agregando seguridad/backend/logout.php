<?php
session_start();


if (setcookie("token", "", time() - 3900, "/", "localhost", false, true)) {
    echo "Cookie eliminada.<br>";
} else {
    echo "Error al eliminar la cookie.<br>";
}


$_SESSION = array();
echo "Variables de sesión eliminadas.<br>";


if (session_destroy()) {
    echo "Sesión destruida.<br>";
} else {
    echo "Error al destruir la sesión.<br>";
}

header("Location: http://localhost/SETS/");
exit();
?>