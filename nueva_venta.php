<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #f5f7f9;
        margin: 0;
        padding: 0;
    }
    nav {
        background-color: #333; /* Fondo del menú */
        color: #fff; /* Texto blanco en el menú */
        padding: 10px;
        text-align: center;
    }
    nav a {
        color: #fff; /* Enlaces en blanco */
        text-decoration: none;
        margin: 0 10px;
    }
    nav a:hover {
        text-decoration: underline;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #007bff;
        color: white;
    }
    tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    tbody tr:hover {
        background-color: #e9ecef;
    }
    .search-container {
        margin-top: 20px;
        text-align: center;
    }
    .search-container input[type=text] {
        padding: 10px;
        margin-right: 10px;
        width: 250px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }
    .search-container button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .search-container button:hover {
        background-color: #0056b3;
    }
    .btn-custom {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-custom:hover {
        background-color: #0056b3;
    }
</style>



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
if ($role == '1'){
    include("menu.php");
}elseif ($role == "2") {
    include("meno_cliente.php");
} 

// Conectar a la base de datos
include("conexion.php");


?>





    <div class="container">
        <h3>Registro de Venta</h3>
        <p>Por favor ingrese todos los datos de su venta</p>

        <div class="input-group mb-3">
            <input type="text" class="form-control" id="idCliente" placeholder="Ingrese el número de identificación">
            <button class="btn btn-outline-secondary" onclick="consultarCliente();">Buscar</button>
        </div>
        <input type="text" readonly class="form-control-plaintext" id="nombreCliente" value="Cliente">
        <button class="btn btn-primary btn-sm" onclick="insertarFactura();">Iniciar Factura</button>
        <h5 id="numeroFactura">Factura:</h5>
        <h4>Agregar un producto</h4>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="txtCantidad" placeholder="Cantidad">
        </div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="codigoProducto" placeholder="Ingrese el nombre del producto">
            <button class="btn btn-outline-secondary" onclick="buscarProducto();">Buscar</button>
        </div>

        <h4>Productos seleccionados</h4>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Eliminar</th>
                </tr>
            </thead>
            <tbody id="resultadoProducto"></tbody>
        </table>

        <h4 id="subtotal">Sub Total:</h4>
        <h4 id="iva">IVA %:</h4>
        <h4 id="total">Total Factura:</h4>
        <button class="btn btn-success btn-sm" onclick="imprimirFactura();">Finalizar Factura</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    var id = 0; // ID del cliente
    var idFactura = 0;
    var Iva = 0;
    var Total = 0;
    var SubtotalGeneral = 0;

    function consultarCliente() {
        var idCliente = document.getElementById("idCliente").value;
        $.ajax({
            url: 'consultar_cliente_factura.php',
            method: 'POST',
            data: { idCliente: idCliente },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById("nombreCliente").value = data.cli_nom + " " + data.cli_ape;
                    id = data.cli_id;
                }
            }
        });
    }

    function insertarFactura() {
        $.ajax({
            url: 'iniciar_factura.php',
            method: 'POST',
            data: { cli_id: id },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    var factura_id = data.factura_id;
                    document.getElementById("numeroFactura").innerText = "Factura ID: " + factura_id;
                    idFactura = factura_id;
                } else {
                    alert("Error al insertar la factura: " + data.error);
                }
            }
        });
    }

    function buscarProducto() {
        var codigoProducto = document.getElementById("codigoProducto").value;
        var cant = document.getElementById("txtCantidad").value;

        $.ajax({
            url: 'buscar_producto_factura.php',
            method: 'POST',
            data: { codigoProducto: codigoProducto },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else if (data.pro_estado == 0) {
                    alert("No hay cantidad suficiente de este producto o el producto ya se ha agotado.");
                } else {
                    var resultadoProducto = document.getElementById("resultadoProducto");
                    var productoExistente = false;

                    var filas = resultadoProducto.getElementsByTagName("tr");
                    for (var i = 0; i < filas.length; i++) {
                        var celdas = filas[i].getElementsByTagName("td");
                        if (celdas[0] && celdas[0].innerText == data.pro_id) {
                            productoExistente = true;
                            break;
                        }
                    }

                    if (!productoExistente) {
                        var fila = document.createElement("tr");
                        let subTotal = data.pro_pre * cant;
                        SubtotalGeneral += subTotal;
                        Iva = SubtotalGeneral * 0.19;
                        Total = SubtotalGeneral + Iva;

                        fila.setAttribute('data-producto-id', data.pro_id);
                        fila.setAttribute('data-factura-id', idFactura);

                        fila.innerHTML = "<td>" + data.pro_id + "</td>" +
                                         "<td>" + data.pro_nom + "</td>" +
                                         "<td>" + data.pro_pre + "</td>" +
                                         "<td>" + cant + "</td>" +
                                         "<td>" + subTotal.toFixed(2) + "</td>" +
                                         "<td><button class='btn btn-danger' onclick='EliminarProducto(this)'>Eliminar</button></td>";
                        
                        resultadoProducto.appendChild(fila);

                        document.getElementById("subtotal").innerText = "Sub Total: " + SubtotalGeneral.toFixed(2);
                        document.getElementById("iva").innerText = "IVA 19%: " + Iva.toFixed(2);
                        document.getElementById("total").innerText = "Total Factura: " + Total.toFixed(2);

                        insertarProductoFactura(idFactura, data.pro_id, cant);
                    } else {
                        alert("El producto ya está en la tabla.");
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    }

    function insertarProductoFactura(fac_enc_id, prod_id, fact_det_cant) {
        $.ajax({
            url: 'insertar_producto_factura.php',
            method: 'POST',
            data: {
                fac_enc_id: fac_enc_id,
                prod_id: prod_id,
                fact_det_cant: fact_det_cant
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    console.log("Producto agregado correctamente");
                } else {
                    alert("Error al insertar el producto: " + data.error);
                }
            }
        });
    }

    function EliminarProducto(btn) {
    var fila = btn.parentNode.parentNode;

    var productoId = fila.getAttribute('data-producto-id');
    var facturaId = fila.getAttribute('data-factura-id');
    var cantidad = parseInt(fila.getElementsByTagName("td")[3].innerText);

    // Eliminar visualmente la fila
    fila.parentNode.removeChild(fila);

    // Actualizar los totales de la factura
    SubtotalGeneral -= parseFloat(fila.getElementsByTagName("td")[4].innerText);
    Iva = SubtotalGeneral * 0.19;
    Total = SubtotalGeneral + Iva;

    document.getElementById("subtotal").innerText = "Sub Total: " + SubtotalGeneral.toFixed(2);
    document.getElementById("iva").innerText = "IVA 19%: " + Iva.toFixed(2);
    document.getElementById("total").innerText = "Total Factura: " + Total.toFixed(2);

    // Opcional: Hacer una llamada AJAX para actualizar la base de datos
    if (confirm("¿Estás seguro de que deseas eliminar la factura completa?")) {
        $.ajax({
            url: 'eliminar_producto_factura.php',
            method: 'POST',
            data: { factura_id: facturaId },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    alert("Factura eliminada correctamente.");
                    // Eliminar la fila de la tabla
                    fila.remove();
                    // Actualizar los totales si es necesario
                } else {
                    alert("Error al eliminar la factura: " + data.error);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX: ", status, error);
            }
        });
    }
    var confirmacion = confirm("¿Deseas eliminar esta venta?");
    
    if (confirmacion) {
        var fila = btn.parentNode.parentNode;
        var productoId = fila.getAttribute('data-producto-id');
        var facturaId = fila.getAttribute('data-factura-id');
        
        // Lógica para eliminar el producto de la base de datos o ajustar el subtotal
        // Aquí puedes continuar con la lógica de eliminación existente
        fila.remove(); // Esto elimina la fila de la tabla
        alert("Venta eliminada con éxito");
    } else {
        // Si el usuario cancela la confirmación, no se realiza ninguna acción
        alert("La venta no ha sido eliminada");
    }

}

function imprimirFactura() {
    var facturaId = idFactura;
    if (facturaId === 0) {
        alert("Por favor, inicie una factura primero.");
        return;
    }
    var url = 'hola.php?fac_enc_id=' + facturaId;
    window.open(url, '_blank');
}
</script>
</body>
</html>