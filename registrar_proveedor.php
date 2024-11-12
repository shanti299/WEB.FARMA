<?php
require_once('conexion.php'); // Asegúrate de que la conexión a la base de datos esté configurada aquí

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nom_supplier'];
    $direccion = $_POST['dir_supplier'];
    $tipo = $_POST['tipo_supplier'];
    $registro = $_POST['resa_supplier'];
    $correo = $_POST['core_supplier'];
    $telefono = $_POST['tele_supplier'];

    // Validar y sanitizar datos
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');
    $tipo = htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8');
    $registro = htmlspecialchars($registro, ENT_QUOTES, 'UTF-8');
    $correo = htmlspecialchars($correo, ENT_QUOTES, 'UTF-8');
    $telefono = intval($telefono);

    // Preparar la consulta SQL
    $SQL = "INSERT INTO proveeedor (nom_supplier, dir_supplier, tipo_supplier, resa_supplier, core_supplier, tele_supplier) 
            VALUES (:nombre, :direccion, :tipo, :registro, :correo, :telefono)";
    $stmt = $conexion->prepare($SQL);

    // Ejecutar la consulta
    if ($stmt->execute([
        ':nombre' => $nombre,
        ':direccion' => $direccion,
        ':tipo' => $tipo,
        ':registro' => $registro,
        ':correo' => $correo,
        ':telefono' => $telefono
    ])) {
        echo "Proveedor registrado exitosamente.";
    } else {
        echo "Error al registrar el proveedor.";
    }
} else {
    echo "Método de solicitud no permitido.";
}
?>
