<?php
require_once('conexion.php');

// Verificar si se recibieron los datos necesarios
if (isset($_POST['cli_id']) && isset($_POST['cli_doc']) && isset($_POST['cli_nom']) && isset($_POST['cli_ape'])) {
    
    // Capturar los datos del formulario
    $cli_id = $_POST['cli_id'];
    $cli_doc = $_POST['cli_doc'];
    $cli_nom = $_POST['cli_nom'];
    $cli_ape = $_POST['cli_ape'];
    
    // Preparar la consulta SQL para actualizar el cliente
    $sql = "UPDATE clientes SET cli_doc = :cli_doc, cli_nom = :cli_nom, cli_ape = :cli_ape WHERE cli_id = :cli_id";
    $stmt = $conexion->prepare($sql);
    
    // Asignar los valores a los parámetros
    $stmt->bindParam(':cli_doc', $cli_doc);
    $stmt->bindParam(':cli_nom', $cli_nom);
    $stmt->bindParam(':cli_ape', $cli_ape);
    $stmt->bindParam(':cli_id', $cli_id);
    
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Si la actualización fue exitosa, devolver una respuesta
        echo json_encode(['status' => 'success', 'message' => 'Cliente actualizado correctamente']);
    } else {
        // Si ocurrió un error, devolver una respuesta con error
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el cliente']);
    }
} else {
    // Si no se recibieron los datos, devolver una respuesta con error
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
}
?>
