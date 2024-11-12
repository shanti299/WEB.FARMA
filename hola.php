<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
    /* Estilo para el menú de navegación */
    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: #333; /* Fondo negro */
        color: white;
        position: relative; /* Permite usar position absolute en el logout */
    }
    nav a {
        color: #fff;
        text-decoration: none;
        margin: 0 20px;
    }
    nav img {
        height: 75px; /* Mantiene la imagen en su tamaño original */
    }

    /* Estilo general */
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    /* Título y subtítulo */
    h1 {
        color: #2fb4cc;
        text-align: center;
        font-size: 32px;
        margin-bottom: 20px;
        background-color: #2fb4cc;
        color: white;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
        padding: 10px;
        border-radius: 8px;
    }
    h2 {
        color: #7a7a7a;
        text-align: center;
        font-size: 24px;
        margin-bottom: 10px;
    }

    /* Estilo para los detalles de la factura */
    .detalles-factura {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 1px 10px rgba(0,0,0,0.1);
        margin: 20px auto;
        max-width: 600px; /* Max width para centrado */
    }

    .detalle {
        margin-bottom: 10px;
        font-size: 18px;
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .detalle strong {
        color: #2fb4cc;
    }

    /* Estilo para la sección de detalles */
    .info-detalles {
        font-size: 16px;
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f4f4f9; /* Fondo suave */
        border-radius: 5px;
        box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
    }

    .info-detalles span {
        font-weight: bold;
        color: #2fb4cc; /* Color del texto importante */
    }

    /* Tabla de factura */
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px auto;
        background-color: #fff;
        box-shadow: 0px 1px 10px rgba(0,0,0,0.1);
    }
    table, th, td {
        border: 1px solid #ddd;
    }
    th, td {
        padding: 12px;
        text-align: left;
        color: #6a6a6a;
    }
    th {
        background-color: #2fb4cc;
        color: white;
        text-transform: uppercase;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #ddd;
    }

    /* Totales */
    .total {
        font-weight: bold;
    }
    .total-iva {
        color: #ff6600;
    }

    button {
        display: block;
        width: 200px;
        margin: 20px auto;
        padding: 10px;
        background-color: #2fb4cc;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #1f95b2;
    }
</style>
    <script>
        function imprimirFactura(fac_enc_id) {
            window.open('generar_pdf.php?fac_enc_id=' + fac_enc_id, '_blank');
        }
    </script>
</head>

<body>

<?php
session_start();
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el rol del usuario
$role = $_SESSION['role'];
?>

<?php 
if ($role == '1') {
    include("menu.php");
} elseif ($role == "2") {
    include("meno_cliente.php");
} 
?>

<h2>Buscar Factura</h2>
<form method="get" action="">
    <label for="search_id">ID de Factura:</label>
    <input type="number" id="search_id" name="fac_enc_id" required>
    <input type="submit" value="Buscar">
</form>

<?php
require("conexion.php");

// Verificar la conexión
if ($conexion === null) {
    die("Conexión fallida.");
}

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

            // Mostrar los detalles de la factura
            echo "<html><head><style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h1 {
                        color: #333;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        color: #333;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    tr:hover {
                        background-color: #ddd;
                    }
                    .total {
                        font-weight: bold;
                    }
                    .total-iva {
                        color: #ff6600;
                    }
                    </style></head><body>";

            echo "<h1>Factura #" . htmlspecialchars($factura['fac_enc_id']) . "</h1>";
            echo "<p>Fecha: " . htmlspecialchars($factura['fac_enc_fecha']) . "</p>";
            echo "<p>Cliente: " . htmlspecialchars($factura['cli_nom']) . " " . htmlspecialchars($factura['cli_ape']) . "</p>";
            echo "<p>Documento: " . htmlspecialchars($factura['cli_doc']) . "</p>";

            // Consultar los detalles de los productos en la factura
            $sql_det = "SELECT p.pro_nom, p.pro_desc, p.pro_pre, d.fact_det_cant
                        FROM factura_det_pro d
                        INNER JOIN productos p ON d.pro_id = p.pro_id
                        WHERE d.fac_enc_id = :fac_enc_id";
            $stmt_det = $conexion->prepare($sql_det);
            $stmt_det->bindParam(':fac_enc_id', $fac_enc_id, PDO::PARAM_INT);
            $stmt_det->execute();

            if ($stmt_det->rowCount() > 0) {
                echo "<table>
                        <tr>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>";

                $total = 0;
                while ($row = $stmt_det->fetch(PDO::FETCH_ASSOC)) {
                    $subtotal = $row['fact_det_cant'] * $row['pro_pre'];
                    echo "<tr>
                            <td>" . htmlspecialchars($row['pro_nom']) . "</td>
                            <td>" . htmlspecialchars($row['pro_desc']) . "</td>
                            <td>" . htmlspecialchars($row['fact_det_cant']) . "</td>
                            <td>" . number_format($row['pro_pre'], 2) . "</td>
                            <td>" . number_format($subtotal, 2) . "</td>
                          </tr>";
                    $total += $subtotal;
                }

                // Calcular IVA (19%)
                $iva = $total * 0.19;
                $total_con_iva = $total + $iva;

                echo "<tr class='total'>
                        <td colspan='4' align='right'><strong>Total</strong></td>
                        <td><strong>" . number_format($total, 2) . "</strong></td>
                      </tr>";
                echo "<tr class='total total-iva'>
                        <td colspan='4' align='right'><strong>IVA (19%)</strong></td>
                        <td><strong>" . number_format($iva, 2) . "</strong></td>
                      </tr>";
                echo "<tr class='total'>
                        <td colspan='4' align='right'><strong>Total con IVA</strong></td>
                        <td><strong>" . number_format($total_con_iva, 2) . "</strong></td>
                      </tr>";
                echo "</table>";

                // Botón para imprimir la factura
                echo "<button onclick='imprimirFactura(" . htmlspecialchars($factura['fac_enc_id']) . ")'>Imprimir Factura</button>";

            } else {
                echo "<p>No se encontraron detalles de productos para esta factura.</p>";
            }
        } else {
            echo "<p>No se encontró la factura.</p>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
</body>
</html>
