<?php
require_once('conexion.php'); // Asegúrate de tener la conexión a la base de datos aquí

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usar el operador ternario para verificar si las claves existen en $_POST
    $producto_id = isset($_POST['producto_id']) ? $_POST['producto_id'] : null;
    $cantidad_ingreso = isset($_POST['cantidad_ingreso']) ? $_POST['cantidad_ingreso'] : null;
    $proveedor_id = isset($_POST['proveedor_id']) ? $_POST['proveedor_id'] : null;

    if ($producto_id && $cantidad_ingreso && $proveedor_id) {
        $fecha_ingreso = date('Y-m-d H:i:s'); // Fecha y hora actual

        // Inserta el ingreso en la base de datos
        $SQL = 'INSERT INTO ingresos (fecha_ingreso, producto_id, cantidad_ingreso, proveedor_id) VALUES (:fecha_ingreso, :producto_id, :cantidad_ingreso, :proveedor_id)';
        $stmt = $conexion->prepare($SQL);
        $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
        $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad_ingreso', $cantidad_ingreso, PDO::PARAM_INT);
        $stmt->bindParam(':proveedor_id', $proveedor_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error al registrar el ingreso.";
        }
    } else {
        echo "Error: Datos de ingreso incompletos.";
    }
}
?>
