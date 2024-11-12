<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
    <link rel="stylesheet" href="estilos/style.css">
</head>
<body>
<style>
/* Estilos generales */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #ffffff; /* Fondo gris claro para el cuerpo */
}

.table {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

/* Estilo para la cabecera de la tabla */
.table th {
    background-color: #007bff; /* Cabecera de tabla azul */
    color: white;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 123, 255, 0.1); /* Fila de tabla azul clara */
}

.search-container {
    margin: 20px 0;
}

.search-container input {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.search-container button {
    padding: 6px 10px;
    background-color: #0056b3; /* Azul oscuro para el botón de buscar */
    color: white; /* Texto en blanco */
    border: none;
    border-radius: 4px; /* Bordes redondeados */
    cursor: pointer;
}

/* Estilos para el Modal */
#providerModal {
    display: none; /* Oculto por defecto */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: #ffffff; /* Fondo blanco para el contenido del modal */
    padding: 20px;
    border: 1px solid #888;
    border-radius: 8px;
    width: 400px;
    position: relative;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.3s ease-in-out;
}

.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    color: #333;
}

.close:hover {
    color: #f44336;
}

.modal-content h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 22px;
    text-align: center;
    color: #333;
}

.modal-content label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

.modal-content input {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.modal-content button {
    background-color: #28a745; /* Verde para el botón de añadir/actualizar */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.modal-content button:hover {
    background-color: #218838; /* Verde más oscuro al pasar el cursor */
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

    </style>
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

<div class="search-container">
    <input type="text" id="searchInput" placeholder="Buscar medicamento...">
    <button type="button" onclick="searchMedicine()">Buscar</button>
</div>

<div class="container">
    <div class="container-sm">
      <table class="table caption-top">
        <caption>Lista de Medicamentos</caption>
        <thead>
          <tr>
            <th scope="col">Código</th>
            <th scope="col">Nombre</th>
            <th scope="col">Descripción</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Tipo</th>
            <th scope="col">Precio</th>
            <th scope="col">Cantidad (UND)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          require_once('conexion.php');
          $SQL = 'SELECT pro_cod, pro_nom, pro_desc, pro_prov, pro_tipo, pro_pre, pro_cantidad FROM productos';
          $stmt = $conexion->prepare($SQL);
          $result = $stmt->execute();
          $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          foreach ($rows as $row) {
            ?>
            <tr>
              <td><?php print($row['pro_cod']) ?></td>
              <td><?php print($row['pro_nom']) ?></td>
              <td><?php print($row['pro_desc']) ?></td>
              <td><a href="#" onclick="mostrarProveedorModal('<?php print($row['pro_prov']) ?>')"><?php print($row['pro_prov']) ?></a></td>
              <td><?php print($row['pro_tipo']) ?></td>
              <td><?php print($row['pro_pre']) ?></td>
              <td id="cantidad_<?php print($row['pro_cod']) ?>"><?php print($row['pro_cantidad']) ?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>
    </div>
</div>

<!-- Modal de Proveedor -->
<div id="providerModal" onclick="cerrarModal(event, 'providerModal')">
    <div class="modal-content">
        <span onclick="cerrarProveedorModal()" class="close">&times;</span>
        <h2>Detalles del Proveedor</h2>
        <div id="providerDetails">
            <!-- Aquí se mostrarán los detalles del proveedor -->
        </div>
    </div>
</div>
<script>

function searchMedicine() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector("table");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
function mostrarProveedorModal(proveedor) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "proveedor.php?proveedor=" + proveedor, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById("providerDetails").innerHTML = xhr.responseText;
            document.getElementById("providerModal").style.display = "flex";
        } else {
            alert("Error al cargar los detalles del proveedor.");
        }
    };
    xhr.send();
}

function cerrarProveedorModal() {
    document.getElementById("providerModal").style.display = "none";
}

function cerrarModal(event, modalId) {
    if (event.target.id === modalId) {
        document.getElementById(modalId).style.display = "none";
    }
}
</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/und/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
