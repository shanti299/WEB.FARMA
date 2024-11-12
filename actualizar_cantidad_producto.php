<?php
session_start();
// Incluir la conexión a la base de datos aquí

$productoId = $_POST['productoId'];
$cantidad = $_POST['cantidad'];

// Asegúrate de que la cantidad sea no negativa
if ($cantidad < 0) {
    echo json_encode(['error' => 'La cantidad no puede ser negativa.']);
    exit();
}

$query = "UPDATE productos SET pro_cantidad = $cantidad WHERE pro_id = $productoId";
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al actualizar la cantidad: ' . mysqli_error($conn)]);
}
?>
