<?php
// eliminar_factura.php
if (isset($_POST['factura_id'])) {
    $factura_id = $_POST['factura_id'];

    // Conexión a la base de datos
    include("conexion.php");

    // Iniciar transacción para asegurarse de que ambas eliminaciones se hagan correctamente
    $conn->begin_transaction();

    try {
        // Eliminar los detalles de la factura
        $query = "DELETE FROM factura_det_pro WHERE fac_enc_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $factura_id);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("No se encontraron detalles para la factura.");
        }

        // Eliminar la factura de la tabla principal
        $query = "DELETE FROM factura_enc WHERE fac_enc_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $factura_id);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            throw new Exception("No se encontró la factura.");
        }

        // Si todo fue exitoso, confirmar la transacción
        $conn->commit();
        echo json_encode(["success" => true]);

    } catch (Exception $e) {
        // Si hay un error, revertir la transacción
        $conn->rollback();
        echo json_encode(["error" => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
}
?>

