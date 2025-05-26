
<?php
function actualizarZona($db, $idZona, $descripcion, $url_videos)
{
    $query = "UPDATE zona_comun SET descripcion = :descripcion, url_videos = :url_videos WHERE idZona = :idZona";
    $statement = $db->prepare($query);
    $statement->bindParam(':descripcion', $descripcion);
    $statement->bindParam(':url_videos', $url_videos);
    $statement->bindParam(':idZona', $idZona);
    return $statement->execute();
}

function eliminarZona($db, $idZona)
{
    $db->beginTransaction();

    try {
        $sql = "DELETE FROM zona_comun WHERE idZona = ?";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$idZona]);

        $db->commit();
        return $result;
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}
?>