<?php
require_once("conexion.php");

try{

    $idCliente = $_POST["idCliente"];
    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE cli_doc = :id");
    $stmt->bindParam('id', $idCliente, PDO:: PARAM_INT);
    $stmt->execute();

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if($cliente){
        $response = array(
            'cli_nom'=> $cliente['cli_nom'],
            'cli_ape'=> $cliente['cli_ape'],
            'cli_id'=> $cliente['cli_id'],
        );
        echo json_encode($response);

    }else {
        echo json_encode(array('error' => 'Cliente no encontrado'));
    }

} catch (PDOException $e) {

    echo json_encode(array('error' => 'Error de conexión'.$e->getMessage()));

}

?>