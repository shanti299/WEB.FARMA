<?php
require_once("conexion.php");

try{

    $cli_id = $_POST["cli_id"];
    $estado = "1";
    $stmt = $conexion->prepare("INSERT INTO factura_enc (cli_id, fac_enc_est) values (:cli_id, :fac_enc_est)");
    $stmt->bindParam(':cli_id', $cli_id, PDO:: PARAM_INT);
    $stmt->bindParam(':fac_enc_est', $estado, PDO::PARAM_INT);
    $stmt->execute();

    $factura_id = $conexion->lastInsertId();
    echo json_encode(array('success'=>true, 'factura_id'=> $factura_id));



} catch (PDOException $e) {

    echo json_encode(array('error' => 'Error al ingresar la factura'.$e->getMessage()));

}

