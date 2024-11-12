<?php
require_once('conexion.php');

if (isset($_GET['proveedor'])) {
    $proveedor = $_GET['proveedor'];
    
    // Preparar y ejecutar la consulta para obtener los detalles del proveedor
    $SQL = 'SELECT * FROM proveeedor WHERE nom_supplier = :proveedor';
    $stmt = $conexion->prepare($SQL);
    $stmt->bindParam(':proveedor', $proveedor, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            // Mostrar los detalles del proveedor en formato HTML
            echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($result['nom_supplier']) . '</p>';
            echo '<p><strong>Dirección:</strong> ' . htmlspecialchars($result['dir_supplier']) . '</p>';
            echo '<p><strong>Tipo:</strong> ' . htmlspecialchars($result['tipo_supplier']) . '</p>';
            echo '<p><strong>Registro Sanitario:</strong> ' . htmlspecialchars($result['resa_supplier']) . '</p>';
            echo '<p><strong>Correo:</strong> ' . htmlspecialchars($result['core_supplier']) . '</p>';
            echo '<p><strong>Teléfono:</strong> ' . htmlspecialchars($result['tele_supplier']) . '</p>';
        } else {
            echo 'Proveedor no encontrado.';
        }
    } else {
        echo 'Error en la consulta.';
    }
} else {
    echo 'Proveedor no especificado.';
}
?>