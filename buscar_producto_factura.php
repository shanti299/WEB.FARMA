<?php
require_once("conexion.php");

try{

    $idPro = $_POST["codigoProducto"];
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE pro_nom = :codigoProducto");
    $stmt->bindParam(':codigoProducto', $idPro, PDO:: PARAM_STR);
    $stmt->execute();

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        echo json_encode($producto);
    }else {
        echo json_encode(array('error' => 'Producto no encontrado'));
    }


} catch (PDOException $e) {

    echo json_encode(array('error' => 'Error de conexión'.$e->getMessage()));

}

?>