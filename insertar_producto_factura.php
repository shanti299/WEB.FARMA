<?php
require_once("conexion.php");

try{

    $fact_enc_id = $_POST["fac_enc_id"];
    $pro_id = $_POST["prod_id"];
    $cant = $_POST["fact_det_cant"];
    $stmt = $conexion->prepare("INSERT INTO factura_det_pro (fac_enc_id, pro_id, fact_det_cant) values (:fac_enc_id, :pro_id, :fact_det_cant);");
    $stmt->bindParam(':fac_enc_id', $fact_enc_id, PDO:: PARAM_INT);
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->bindParam(':fact_det_cant', $cant, PDO::PARAM_INT);
    $stmt->execute();

  
    echo json_encode(array('success'=>true));



} catch (PDOException $e) {

    echo json_encode(array('success' => 'False','error'=>'error al insertar el producto'.$e->getMessage()));

}

