<?php
include_once './conexion.php';
include_once './edzonas.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['idZona']) && isset($_POST['descripcion']) && isset($_POST['url_videos'])) {
        try {
            if (actualizarZona($base_de_datos, $_POST['idZona'], $_POST['descripcion'], $_POST['url_videos'])) {
                header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=update_success");
            } else {
                header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=update_error");
            }
            exit();
        } catch (Exception $e) {
            header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=update_error&message=".urlencode($e->getMessage()));
            exit();
        }
    }
    

    if (isset($_POST['idZona']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
        try {
            if (eliminarZona($base_de_datos, $_POST['idZona'])) {
                header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=delete_success");
            } else {
                header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=delete_error");
            }
            exit();
        } catch (Exception $e) {
            header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php?status=delete_error&message=".urlencode($e->getMessage()));
            exit();
        }
    }
}

header("Location: /SETS-PROYECTO/frontend-web/admin/zonas_comunes.php");
exit();
?>
