<?php
include_once './conexion.php';
include_once './procesarmoto.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'], $_POST['id_solicitud'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $accion = $_POST['accion'];
    $resultado = false;

    switch ($accion) {
        case 'aprobado':
            $resultado = actualizarEstadoParking($base_de_datos, $id_solicitud, 'aprobado');
            $mensaje = $resultado ? 'aprobado_exito' : 'aprobado_error';
            break;
            
        case 'pendiente':
            $resultado = actualizarEstadoParking($base_de_datos, $id_solicitud, 'pendiente');
            $mensaje = $resultado ? 'pendiente_exito' : 'pendiente_error';
            break;
            
        case 'rechazado':
            $resultado = eliminarSolicitudParking($base_de_datos, $id_solicitud);
            $mensaje = $resultado ? 'rechazado_exito' : 'rechazado_error';
            break;
            
        default:
            $mensaje = 'accion_invalida';
    }

    header("Location: /SETS-PROYECTO/frontend-web/admin/hoariomoto.php?mensaje=$mensaje");
    exit();
}

header("Location: /SETS-PROYECTO/frontend-web/admin/hoariomoto.php");
exit();
?>
