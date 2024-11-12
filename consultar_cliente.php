<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia - Clientes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa; /* Fondo claro para toda la página */
    }
    nav {
        background-color: #333; /* Fondo del menú */
        color: #fff; /* Texto blanco en el menú */
        padding: 10px;
        text-align: center;
    }
    nav a {
        color: #fff; /* Enlaces en blanco */
        text-decoration: none; /* Sin subrayado por defecto */
        margin: 0 10px;
    }
    nav a:hover {
        text-decoration: underline; /* Subraya el texto del enlace al pasar el cursor */
        text-decoration-color: white; /* Asegura que el subrayado sea blanco */
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd; /* Borde suave */
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #007bff; /* Azul para la cabecera de la tabla */
        color: white; /* Texto blanco en la cabecera */
    }
    td {
        background-color: #f8f9fa; /* Fondo claro para las celdas */
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 123, 255, 0.1); /* Fila de tabla azul clara */
    }
    .search-container {
        margin-top: 10px;
        text-align: center;
    }
    .search-container input[type=text] {
        padding: 6px;
        margin-right: 5px;
        width: 200px;
        box-sizing: border-box;
        border: 1px solid #ccc; /* Borde suave */
        border-radius: 4px; /* Bordes redondeados */
    }
    .search-container button {
        padding: 6px 10px;
        background-color: #0056b3; /* Azul oscuro para el botón de buscar */
        color: white; /* Texto en blanco */
        border: none;
        border-radius: 4px; /* Bordes redondeados */
        cursor: pointer;
    }
    .btnDanger {
        padding: 6px 10px;
        background-color: #d43833; /* Rojo para el botón peligro */
        color: white; /* Texto blanco */
        border: none;
        border-radius: 4px; /* Bordes redondeados */
        cursor: pointer;
    }
    .btnSuccess {
        padding: 6px 10px;
        background-color: #4CAF50; /* Verde para el botón de actualizar */
        color: white; /* Texto blanco */
        border: none;
        border-radius: 4px; /* Bordes redondeados */
        cursor: pointer;
    }
    .search-container button:hover {
        background-color: #0056b3; /* Cambia el fondo a un azul más oscuro al pasar el cursor */
    }
    .btn-custom:hover {
        background-color: #0056b3; /* Cambia el fondo a un azul más oscuro al pasar el cursor */
    }
</style>

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

<div class="search-container">
    <input type="text" id="searchInput" placeholder="Buscar cliente...">
    <button type="button" onclick="searchClient()">Buscar</button>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerClientModal">Registrar Cliente</button>
</div>

<div class="container">
    <div class="container-sm">
        <table class="table caption-top">
            <caption>Lista de Clientes</caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Documento</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Actualizar</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody id="clientTable">
                <?php
                require_once('conexion.php');
                $SQL = 'SELECT cli_id, cli_doc, cli_nom, cli_ape FROM clientes';
                $stmt = $conexion->prepare($SQL);
                $stmt->execute();
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    echo "<tr>
                            <td>{$row['cli_id']}</td>
                            <td>{$row['cli_doc']}</td>
                            <td>{$row['cli_nom']}</td>
                            <td>{$row['cli_ape']}</td>
                            <td><button class='btn btn-success ' onclick='openUpdateModal({$row['cli_id']}, \"{$row['cli_doc']}\", \"{$row['cli_nom']}\", \"{$row['cli_ape']}\")'>Actualizar</button></td>
                            <td><button class='btn btn-danger' onclick='confirmDelete({$row['cli_id']})'>Eliminar</button></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para registrar cliente -->
<div class="modal fade" id="registerClientModal" tabindex="-1" aria-labelledby="registerClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerClientModalLabel">Registrar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registerClientForm">
                    <div class="form-group">
                        <label for="cli_doc">Documento</label>
                        <input type="text" class="form-control" id="cli_doc" name="cli_doc" required>
                    </div>
                    <div class="form-group">
                        <label for="cli_nom">Nombre</label>
                        <input type="text" class="form-control" id="cli_nom" name="cli_nom" required>
                    </div>
                    <div class="form-group">
                        <label for="cli_ape">Apellido</label>
                        <input type="text" class="form-control" id="cli_ape" name="cli_ape" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="registerClient()">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para actualizar cliente -->
<div class="modal fade" id="updateClientModal" tabindex="-1" aria-labelledby="updateClientModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateClientModalLabel">Actualizar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateClientForm">
                    <input type="hidden" id="update_cli_id" name="cli_id">
                    <div class="form-group">
                        <label for="update_cli_doc">Documento</label>
                        <input type="text" class="form-control" id="update_cli_doc" name="cli_doc" required>
                    </div>
                    <div class="form-group">
                        <label for="update_cli_nom">Nombre</label>
                        <input type="text" class="form-control" id="update_cli_nom" name="cli_nom" required>
                    </div>
                    <div class="form-group">
                        <label for="update_cli_ape">Apellido</label>
                        <input type="text" class="form-control" id="update_cli_ape" name="cli_ape" required>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="updateClient()">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Función para buscar clientes en la tabla
function searchClient() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector("table");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2]; // Busca en la columna de nombre
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

// Función para registrar un nuevo cliente
function registerClient() {
    var cli_doc = document.getElementById('cli_doc').value;
    var cli_nom = document.getElementById('cli_nom').value;
    var cli_ape = document.getElementById('cli_ape').value;

    $.ajax({
        url: 'registro_cliente.php',
        type: 'POST',
        data: {
            cli_doc: cli_doc,
            cli_nom: cli_nom,
            cli_ape: cli_ape
        },
        success: function(response) {
            $('#registerClientModal').modal('hide');
            var newRow = `<tr>
                            <td>${response.cli_id}</td>
                            <td>${cli_doc}</td>
                            <td>${cli_nom}</td>
                            <td>${cli_ape}</td>
                          </tr>`;
            document.getElementById('clientTable').innerHTML += newRow;
        }
    });
}

// Abre el modal y llena los campos con los datos actuales del cliente
function openUpdateModal(id, doc, nom, ape) {
    document.getElementById('update_cli_id').value = id;
    document.getElementById('update_cli_doc').value = doc;
    document.getElementById('update_cli_nom').value = nom;
    document.getElementById('update_cli_ape').value = ape;
    $('#updateClientModal').modal('show');
}

// Función para actualizar el cliente
function updateClient() {
    var cli_id = document.getElementById('update_cli_id').value;
    var cli_doc = document.getElementById('update_cli_doc').value;
    var cli_nom = document.getElementById('update_cli_nom').value;
    var cli_ape = document.getElementById('update_cli_ape').value;

    $.ajax({
        url: 'actualizar_cliente.php',
        type: 'POST',
        data: {
            cli_id: cli_id,
            cli_doc: cli_doc,
            cli_nom: cli_nom,
            cli_ape: cli_ape
        },
        success: function(response) {
            $('#updateClientModal').modal('hide');
            var row = document.querySelector(`tr td:first-child:contains(${cli_id})`).parentNode;
            row.cells[1].textContent = cli_doc;
            row.cells[2].textContent = cli_nom;
            row.cells[3].textContent = cli_ape;
        }
    });
}

// Función para eliminar un cliente
// Función para confirmar la eliminación de un cliente
function confirmDelete(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
        // Crear un formulario para enviar la solicitud POST
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "eliminar_cliente.php";
        
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "cli_id";  // Asegúrate de que el nombre coincide con lo que espera el PHP
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}


</script>

</body>
</html>
