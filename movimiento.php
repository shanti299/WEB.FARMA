<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos de Ventas</title>
    <link rel="stylesheet" href="estilos/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            background-image: url('fondo-farmacia.jpg'); /* Cambia esto por la ruta de tu imagen de fondo */
            background-size: cover;
            background-attachment: fixed;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semi-transparente */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #007bff; /* Color azul para el título */
        }
        label {
            font-weight: bold;
        }
        .table th {
            background-color: #007bff; /* Cabecera de tabla azul */
            color: white;
        }
        .table td {
            background-color: #f8f9fa; /* Fondo claro para las celdas */
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.1); /* Fila de tabla azul clara */
        }
        nav a:hover {
    text-decoration: underline; /* Subraya el texto del enlace al pasar el cursor */
}
.search-container button:hover {
    background-color: #0056b3; /* Cambia el fondo a un azul más oscuro al pasar el cursor */
}
.btn-custom:hover {
    background-color: #0056b3; /* Cambia el fondo a un azul más oscuro al pasar el cursor */
}

    </style>
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
if ($role == '1'){
    include("menu.php");
}elseif ($role == "2") {
    include("meno_cliente.php");
} 
?>

<div class="container">
    <h2 class="my-4">Movimientos de Ventas</h2>

    <!-- Formulario para seleccionar día o mes -->
    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fecha" class="form-label">Seleccione un día</label>
                <input type="date" name="fecha" id="fecha" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="mes" class="form-label">Seleccione un mes</label>
                <input type="month" name="mes" id="mes" class="form-control">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID Factura</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">IVA</th>
                    <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('conexion.php');

                // Variables para almacenar los filtros
                $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : null;
                $mes = isset($_POST['mes']) ? $_POST['mes'] : null;
                $filtro = "";
                
                if ($fecha) {
                    $filtro = "WHERE DATE(f.fac_enc_fecha) = '$fecha'";
                } elseif ($mes) {
                    $filtro = "WHERE DATE_FORMAT(f.fac_enc_fecha, '%Y-%m') = '$mes'";
                }

                // Consulta SQL modificada para filtrar por día o mes
                $SQL = "
                    SELECT f.fac_enc_id AS factura_id, f.fac_enc_fecha AS fecha, c.cli_nom AS cliente, p.pro_nom AS producto,
                           d.fact_det_cant AS cantidad, 
                           (d.fact_det_cant * p.pro_pre) AS subtotal, 
                           ((d.fact_det_cant * p.pro_pre) * 0.19) AS iva, 
                           ((d.fact_det_cant * p.pro_pre) + ((d.fact_det_cant * p.pro_pre) * 0.19)) AS total
                    FROM factura_det_pro d
                    JOIN factura_enc f ON d.fac_enc_id = f.fac_enc_id
                    JOIN clientes c ON f.cli_id = c.cli_id
                    JOIN productos p ON d.pro_id = p.pro_id
                    $filtro
                    ORDER BY f.fac_enc_fecha DESC
                ";

                try {
                    $stmt = $conexion->prepare($SQL);
                    $stmt->execute();
                    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $total_vendido = 0;
                    foreach ($movimientos as $movimiento) {
                        echo "<tr>
                            <td>{$movimiento['factura_id']}</td>
                            <td>{$movimiento['fecha']}</td>
                            <td>{$movimiento['cliente']}</td>
                            <td>{$movimiento['producto']}</td>
                            <td>{$movimiento['cantidad']}</td>
                            <td>{$movimiento['subtotal']}</td>
                            <td>{$movimiento['iva']}</td>
                            <td>{$movimiento['total']}</td>
                        </tr>";
                        $total_vendido += $movimiento['total']; // Sumar el total vendido
                    }

                    echo "<tr>
                            <td colspan='7' class='text-end'><strong>Total Vendido:</strong></td>
                            <td><strong>" . number_format($total_vendido, 2) . "</strong></td>
                        </tr>";

                } catch (PDOException $e) {
                    echo "<tr><td colspan='8'>Error: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
