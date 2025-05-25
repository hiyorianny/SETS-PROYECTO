<?php
function actualizarEstadoParking($db, $id_solicitud, $estado) {
    $sql = "UPDATE solicitud_parqueadero SET estado = ? WHERE id_solicitud = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$estado, $id_solicitud]);
}

function eliminarSolicitudParking($db, $id_solicitud) {
    $sql = "DELETE FROM solicitud_parqueadero WHERE id_solicitud = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute([$id_solicitud]);
}
?>
