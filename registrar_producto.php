<?php
require_once('conexion.php'); // Asegúrate de que el archivo conexión establece correctamente la conexión $conexion

// Verificar si se recibieron todos los datos necesarios
if (isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['proveedor']) && isset($_POST['tipo']) && isset($_POST['precio']) && isset($_POST['cantidad'])) {
    // Obtener los datos del producto desde la solicitud
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $proveedor = $_POST['proveedor'];
    $tipo = $_POST['tipo'];
    $precio = floatval($_POST['precio']); // Convertir a número
    $cantidad = intval($_POST['cantidad']); // Convertir a número

    try {
        // Preparar la consulta SQL para insertar el nuevo producto
        $SQL = "INSERT INTO productos (pro_cod, pro_nom, pro_desc, pro_prov, pro_tipo, pro_pre, pro_cantidad)
                VALUES (:codigo, :nombre, :descripcion, :proveedor, :tipo, :precio, :cantidad)";

        $stmt = $conexion->prepare($SQL);

        // Enlazar los parámetros
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':proveedor', $proveedor);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':cantidad', $cantidad);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Retornar una respuesta de éxito
            echo "Producto registrado exitosamente.";
        } else {
            // Retornar el error si la consulta falla
            echo "Error al registrar el producto: " . implode(" ", $stmt->errorInfo());
        }
    } catch (PDOException $e) {
        // Retornar el mensaje de error
        echo "Error de PDO al registrar el producto: " . $e->getMessage();
    }
} else {
    echo "Datos del producto incompletos.";
}
?>
