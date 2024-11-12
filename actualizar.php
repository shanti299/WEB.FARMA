<?php
require_once('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $proveedor = $_POST['proveedor'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $estado = $_POST['estado']; // Obtener el estado del POST

    // Preparar la consulta para actualizar el producto
    $SQL = 'UPDATE productos SET pro_nom = :nombre, pro_desc = :descripcion, pro_prov = :proveedor, pro_tipo = :tipo, pro_pre = :precio, pro_cantidad = :cantidad, pro_estado = :estado WHERE pro_cod = :id';
    $stmt = $conexion->prepare($SQL);
    
    // Ejecutar la consulta
    $result = $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':proveedor' => $proveedor,
        ':tipo' => $tipo,
        ':precio' => $precio,
        ':cantidad' => $cantidad,
        ':estado' => $estado
    ]);

    if ($result) {
        echo "Producto actualizado exitosamente.";
    } else {
        echo "Error al actualizar el producto.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
