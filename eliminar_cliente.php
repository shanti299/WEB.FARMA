<?php
session_start();
require_once('conexion.php'); // Asegúrate de incluir tu archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cli_id'])) {
        $cli_id = $_POST['cli_id'];

        // Cambiar el estado del cliente a 0 (inactivo)
        $SQL = 'UPDATE clientes SET estado = 0 WHERE cli_id = :cli_id';
        $stmt = $conexion->prepare($SQL);
        $stmt->bindParam(':cli_id', $cli_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado del cliente.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del cliente no proporcionado.']);
    }
}
?>
