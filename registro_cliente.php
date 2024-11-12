<?php
require_once('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cli_doc = $_POST['cli_doc'];
    $cli_nom = $_POST['cli_nom'];
    $cli_ape = $_POST['cli_ape'];

    // Preparar e insertar los datos
    $SQL = 'INSERT INTO clientes (cli_doc, cli_nom, cli_ape) VALUES (?, ?, ?)';
    $stmt = $conexion->prepare($SQL);
    
    if ($stmt->execute([$cli_doc, $cli_nom, $cli_ape])) {
        // Obtener el último ID insertado
        $cli_id = $conexion->lastInsertId();

        // Enviar respuesta JSON con el nuevo ID
        echo json_encode(['cli_id' => $cli_id]);
    } else {
        // Enviar un mensaje de error si algo falla
        echo json_encode(['error' => 'Error al registrar el cliente.']);
    }
}
?>
