<?php
require('C:/xampp/htdocs/Farmacia/fpdf/fpdf.php');
require('conexion.php');

// Obtener el ID de la factura desde la URL
$fac_enc_id = isset($_GET['fac_enc_id']) ? intval($_GET['fac_enc_id']) : 0;

if ($fac_enc_id > 0) {
    try {
        // Consultar la información de la factura
        $sql = "SELECT f.fac_enc_id, f.fac_enc_fecha, c.cli_nom, c.cli_ape, c.cli_doc
                FROM factura_enc f
                INNER JOIN clientes c ON f.cli_id = c.cli_id
                WHERE f.fac_enc_id = :fac_enc_id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':fac_enc_id', $fac_enc_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $factura = $stmt->fetch(PDO::FETCH_ASSOC);

            // Crear PDF
            $pdf = new FPDF();
            $pdf->AddPage();

            $pdf->Image('img/images.jpeg', 10, 10, 30); // Ajusta la ruta y el tamaño según sea necesario

            // Encabezado de la empresa
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'FARMACIA NORTE', 0, 1, 'C'); // Nombre de la empresa
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 10, 'Direccion:  Cra. 19 #16-35, San Antonio, Soledad, Atlantico, Colombia', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Telefono: 315 3115008 - NIT: 123456789', 0, 1, 'C');
            $pdf->Cell(0, 10, 'Correo: farmanorte@gmail.com', 0, 1, 'C');
            $pdf->Ln(10); // Espacio

            // Información de la factura y del cliente
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(100, 10, 'Factura #' . $factura['fac_enc_id'], 0, 0);
            $pdf->Cell(90, 10, 'Fecha: ' . $factura['fac_enc_fecha'], 0, 1, 'R');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(100, 10, 'Cliente: ' . $factura['cli_nom'] . ' ' . $factura['cli_ape'], 0, 0);
            $pdf->Cell(90, 10, 'Documento: ' . $factura['cli_doc'], 0, 1, 'R');
            $pdf->Ln(5); // Espacio

            // Consultar los detalles de los productos en la factura
            $sql_det = "SELECT p.pro_nom, p.pro_desc, p.pro_pre, d.fact_det_cant
                        FROM factura_det_pro d
                        INNER JOIN productos p ON d.pro_id = p.pro_id
                        WHERE d.fac_enc_id = :fac_enc_id";
            $stmt_det = $conexion->prepare($sql_det);
            $stmt_det->bindParam(':fac_enc_id', $fac_enc_id, PDO::PARAM_INT);
            $stmt_det->execute();

            if ($stmt_det->rowCount() > 0) {
                // Encabezado de la tabla
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->SetFillColor(200, 200, 200);
                $pdf->Cell(70, 10, 'Producto', 1, 0, 'C', true);
                $pdf->Cell(60, 10, 'Descripcion', 1, 0, 'C', true);
                $pdf->Cell(20, 10, 'Cantidad', 1, 0, 'C', true);
                $pdf->Cell(20, 10, 'Precio', 1, 0, 'C', true);
                $pdf->Cell(20, 10, 'Subtotal', 1, 1, 'C', true);

                $total = 0;
                $pdf->SetFont('Arial', '', 10);

                while ($row = $stmt_det->fetch(PDO::FETCH_ASSOC)) {
                    $subtotal = $row['fact_det_cant'] * $row['pro_pre'];
                    $pdf->Cell(70, 10, utf8_decode($row['pro_nom']), 1);
                    $pdf->Cell(60, 10, utf8_decode($row['pro_desc']), 1);
                    $pdf->Cell(20, 10, $row['fact_det_cant'], 1, 0, 'C');
                    $pdf->Cell(20, 10, number_format($row['pro_pre'], 2), 1, 0, 'C');
                    $pdf->Cell(20, 10, number_format($subtotal, 2), 1, 1, 'C');
                    $total += $subtotal;
                }

                // Calcular IVA (19%)
                $iva = $total * 0.19;
                $total_con_iva = $total + $iva;

                // Mostrar totales
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(150, 10, 'Total antes de IVA', 1);
                $pdf->Cell(20, 10, number_format($total, 2), 1, 1, 'C');
                $pdf->Cell(150, 10, 'IVA (19%)', 1);
                $pdf->Cell(20, 10, number_format($iva, 2), 1, 1, 'C');
                $pdf->Cell(150, 10, 'Total con IVA', 1);
                $pdf->Cell(20, 10, number_format($total_con_iva, 2), 1, 1, 'C');
            } else {
                $pdf->Cell(0, 10, 'No hay detalles de productos para esta factura.', 0, 1);
            }

            // Pie de página
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(0, 10, utf8_decode('Gracias por su compra. ¡Vuelva pronto!'), 0, 1, 'C');
            $pdf->Cell(0, 10, utf8_decode('Esta factura es un documento oficial, conserve una copia.'), 0, 1, 'C');
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'FarmarNorte - www.farmarNorte.com', 0, 1, 'C');
            

            // Salida del PDF
            $pdf->Output('D', 'Factura_' . $factura['fac_enc_id'] . '.pdf');
        } else {
            echo "Factura no encontrada.";
        }
    } catch (PDOException $e) {
        echo "Error al consultar la factura: " . $e->getMessage();
    }
} else {
    echo "ID de factura no válido.";
}

// Cerrar la conexión
$conexion = null;
?>
