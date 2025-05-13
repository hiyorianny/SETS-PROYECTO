<?php
include '../src/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {

        $stmt = $base_de_datos->prepare("SELECT nombre, apellido FROM ciclistas WHERE id = ?");
        $stmt->execute([$id]);
        $ciclista = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ciclista) {

            $stmt = $base_de_datos->prepare("DELETE FROM ciclistas WHERE id = ?");
            $stmt->execute([$id]);
            
            $mensaje = "Ciclista {$ciclista['nombre']} {$ciclista['apellido']} eliminado correctamente";
            header("Location: index.php?success=" . urlencode($mensaje));
        } else {
            header("Location: index.php?error=Ciclista no encontrado");
        }
        exit();
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>
