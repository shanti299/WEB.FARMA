<?php
require_once('conexion.php');

if (isset($_POST['eliminar']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Obtener la cantidad actual del producto
    $SQL = 'SELECT pro_cantidad FROM productos WHERE pro_cod = :id';
    $stmt = $conexion->prepare($SQL);
    $stmt->execute(['id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        if ($producto['pro_cantidad'] > 0) {
            echo -1; // No se puede eliminar, cantidad no es 0
        } else {
            // Actualizar el estado a 0 (no disponible)
            $SQL = 'UPDATE productos SET pro_estado = 0 WHERE pro_cod = :id';
            $stmt = $conexion->prepare($SQL);
            $stmt->execute(['id' => $id]);

            echo 1; // Producto actualizado como no disponible
        }
    } else {
        echo 0; // Producto no encontrado
    }
} else {
    echo 0; // Error en los parámetros recibidos
}
?>
