<?php
require_once('conexion.php');

if (isset($_POST['id']) && isset($_POST['cantidad'])) {
    $id = $_POST['id'];
    $cantidadAgregar = (int)$_POST['cantidad'];

    // Obtener la cantidad actual y el estado del producto
    $SQL = 'SELECT pro_cantidad, pro_estado FROM productos WHERE pro_id = :id'; // Cambia pro_cod a pro_id si corresponde
    $stmt = $conexion->prepare($SQL);
    $stmt->execute(['id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        $nuevaCantidad = $producto['pro_cantidad'] + $cantidadAgregar;
        $nuevoEstado = $nuevaCantidad > 0 ? 1 : 0; // Estado 1 si hay productos, 0 si no

        // Actualizar la cantidad y el estado en la base de datos
        $SQL = 'UPDATE productos SET pro_cantidad = :cantidad, pro_estado = :estado WHERE pro_id = :id'; // Cambia pro_cod a pro_id si corresponde
        $stmt = $conexion->prepare($SQL);
        $stmt->execute(['cantidad' => $nuevaCantidad, 'estado' => $nuevoEstado, 'id' => $id]);

        echo $nuevaCantidad;
    } else {
        echo -1; // Error, producto no encontrado
    }
} else {
    echo -1; // Error en los parámetros recibidos
}
?>
