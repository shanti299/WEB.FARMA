<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
    <link rel="stylesheet" href="estilos/style.css">
    <style>
        
        /* Estilo para el botón de Añadir */
.add-button {
    background-color: #28a745; /* Verde para el botón de añadir */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
}

/* Estilo para el botón de Actualizar */
.update-button {
    background-color: #28a745; /* Azul para el botón de actualizar */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
}

.add-button:hover {
    background-color: #218838; /* Verde más oscuro al pasar el cursor */
}

.update-button:hover {
    background-color: #0056b3; /* Azul más oscuro al pasar el cursor */
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
    .search-container button {
        padding: 6px 10px;
        background-color: #0056b3; /* Azul oscuro para el botón de buscar */
        color: white; /* Texto en blanco */
        border: none;
        border-radius: 4px; /* Bordes redondeados */
        cursor: pointer;
    }
    /* Estilos para el Modal */
    #registerModal, #updateModal, #providerModal, #registerProviderModal {
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
        background-color: #fefefe;
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
?>

<div class="search-container">
    <input type="text" id="searchInput" placeholder="Buscar medicamento...">
    <button type="button" onclick="searchMedicine()">Buscar</button>
    <button type="button" onclick="mostrarRegistroModal()">Registrar nuevo producto</button>
    <button type="button" onclick="mostrarRegistroProveedorModal()">Registrar nuevo proveedor</button>
</div>

<div class="container">
    <div class="container-sm">
        <table class="table caption-top">
            <caption>Lista de medicamentos</caption>
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad (UND)</th>
                    <th scope="col">Eliminar</th>
                    <th scope="col">Añadir</th>
                    <th scope="col">Actualizar</th>
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
                    <tr id="row_<?php print($row['pro_cod']) ?>">
                        <td><?php print($row['pro_cod']) ?></td>
                        <td><?php print($row['pro_nom']) ?></td>
                        <td><?php print($row['pro_desc']) ?></td>
                        <td><a href="#" onclick="mostrarProveedorModal('<?php print($row['pro_prov']) ?>')"><?php print($row['pro_prov']) ?></a></td>
                        <td><?php print($row['pro_tipo']) ?></td>
                        <td><?php print($row['pro_pre']) ?></td>
                        <td id="cantidad_<?php print($row['pro_cod']) ?>"><?php print($row['pro_cantidad']) ?></td>
                        <td>
                            <button class="btnDanger" type="button" onclick="Eliminar(<?php print($row['pro_cod']) ?>)">Eliminar</button>
                        </td>
                        <td>
                            <input type="number" id="add_cant_<?php print($row['pro_cod']) ?>" min="1" placeholder="Cant.">
                            <button type="button" class="add-button" onclick="AñadirCantidad(<?php print($row['pro_cod']) ?>)">Añadir</button>
                        </td>
                        <td>
                            <button type="button"  class="update-button" onclick="mostrarActualizarModal(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)">Actualizar</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de Registro de Proveedor -->
<div id="registerProviderModal" onclick="cerrarModal(event, 'registerProviderModal')">
    <div class="modal-content">
        <span onclick="cerrarRegistroProveedorModal()" class="close">&times;</span>
        <h2>Registrar Nuevo Proveedor</h2>
        <form id="registerProviderForm">
    <label for="nom_supplier">Nombre del Proveedor:</label>
    <input type="text" id="nom_supplier" name="nom_supplier" required>
    <label for="dir_supplier">Dirección:</label>
    <input type="text" id="dir_supplier" name="dir_supplier" required>
    <label for="tipo_supplier">Tipo Proveedor:</label>
    <input type="text" id="tipo_supplier" name="tipo_supplier" required>
    <label for="resa_supplier">Registro Sanitario:</label>
    <input type="text" id="resa_supplier" name="resa_supplier" required>
    <label for="core_supplier">Correo:</label>
    <input type="text" id="core_supplier" name="core_supplier" required>
    <label for="tele_supplier">Teléfono:</label>
    <input type="number" id="tele_supplier" name="tele_supplier" required>
    <button type="button" onclick="registrarProveedor()">Registrar Proveedor</button>
</form>

    </div>
</div>

<!-- Modal de Actualización -->
<div id="updateModal">
    <div class="modal-content">
        <span onclick="cerrarActualizarModal()" class="close">&times;</span>
        <h2>Actualizar Medicamento</h2>
        <form id="updateForm">
            <input type="hidden" name="id" id="update_pro_cod">
            <input type="hidden" name="actualizar" value="1"> <!-- Campo oculto para identificar la acción -->
            <label for="update_pro_nom">Nombre:</label>
            <input type="text" name="nombre" id="update_pro_nom" required>
            <label for="update_pro_desc">Descripción:</label>
            <input type="text" name="descripcion" id="update_pro_desc" required>
            <label for="update_pro_prov">Proveedor:</label>
            <input type="text" name="proveedor" id="update_pro_prov" required>
            <label for="update_pro_tipo">Tipo:</label>
            <input type="text" name="tipo" id="update_pro_tipo" required>
            <label for="update_pro_pre">Precio:</label>
            <input type="number" name="precio" id="update_pro_pre" required min="0" step="0.01">
            <label for="update_pro_cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="update_pro_cantidad" required min="0">
            <button type="button" onclick="actualizarMedicamento()">Actualizar</button>
        </form>
    </div>
</div>


<!-- Modal de Registro -->
<div id="registerModal">
    <div class="modal-content">
        <span onclick="cerrarRegistroModal()" class="close">&times;</span>
        <h2>Registrar Nuevo Producto</h2>
        <form id="registerForm">
    <input type="text" name="codigo" placeholder="Código del producto" required>
    <input type="text" name="nombre" placeholder="Nombre del producto" required>
    <textarea name="descripcion" placeholder="Descripción del producto" required></textarea>
    <input type="text" name="proveedor" placeholder="Proveedor" required>
    <input type="text" name="tipo" placeholder="Tipo de producto" required>
    <input type="number" name="precio" step="0.01" placeholder="Precio" required>
    <input type="number" name="cantidad" placeholder="Cantidad" required>
    <button type="button" onclick="registrarProducto()">Registrar Producto</button>
</form>

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
function Eliminar(id) {
    if (confirm("¿Estás seguro de que deseas eliminar este medicamento?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "eliminar.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response == 1) {
                    document.getElementById("row_" + id).style.display = 'none';
                } else if (response == -1) {
                    alert("No se puede eliminar el producto, la cantidad no es 0.");
                } else {
                    alert("Error al eliminar el medicamento.");
                }
            } else {
                alert("Error al eliminar el medicamento.");
            }
        };
        xhr.send("id=" + id + "&eliminar=1");
    }
}


function AñadirCantidad(id) {
    var cantidad = document.getElementById("add_cant_" + id).value;
    if (cantidad > 0) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "añadir.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                var nuevaCantidad = xhr.responseText;
                if (nuevaCantidad != -1) {
                    document.getElementById("cantidad_" + id).innerText = nuevaCantidad;
                    document.getElementById("add_cant_" + id).value = '';
                } else {
                    alert("Error: Producto no encontrado.");
                }
            } else {
                alert("Error al añadir cantidad.");
            }
        };
        xhr.send("id=" + encodeURIComponent(id) + "&cantidad=" + encodeURIComponent(cantidad));
    } else {
        alert("La cantidad debe ser mayor a 0.");
    }
}


function mostrarRegistroModal() {
    document.getElementById("registerModal").style.display = "flex";
}

function cerrarRegistroModal() {
    document.getElementById("registerModal").style.display = "none";
}

function mostrarActualizarModal(medicamento) {
    document.getElementById("update_pro_cod").value = medicamento.pro_cod;
    document.getElementById("update_pro_nom").value = medicamento.pro_nom;
    document.getElementById("update_pro_desc").value = medicamento.pro_desc;
    document.getElementById("update_pro_prov").value = medicamento.pro_prov;
    document.getElementById("update_pro_tipo").value = medicamento.pro_tipo;
    document.getElementById("update_pro_pre").value = medicamento.pro_pre;
    document.getElementById("update_pro_cantidad").value = medicamento.pro_cantidad;
    document.getElementById("updateModal").style.display = "flex";
}

function cerrarActualizarModal() {
    document.getElementById("updateModal").style.display = "none";
}

function actualizarMedicamento() {
    var cantidad = parseInt(document.getElementById("update_pro_cantidad").value, 10);
    var estado = cantidad > 0 ? 1 : 0; // Cambia el estado basado en la cantidad

    var formData = new FormData(document.getElementById("updateForm"));
    formData.append("estado", estado); // Añade el estado al FormData

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "actualizar.php", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText.trim() === "Producto actualizado exitosamente.") {
                alert("Medicamento actualizado.");
                location.reload();
            } else {
                alert("Error al actualizar el medicamento: " + xhr.responseText);
            }
        } else {
            alert("Error al actualizar el medicamento.");
        }
    };
    xhr.send(formData);
}


function registrarProducto() {
    // Crear objeto FormData a partir del formulario
    var formData = new FormData(document.getElementById("registerForm"));
    
    // Configurar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "registrar_producto.php", true);
    
    // Manejar la respuesta
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText.trim() === "Producto registrado exitosamente.") { // Validar la respuesta del servidor
                alert("Producto registrado exitosamente.");
                location.reload(); // Recargar la página para ver el nuevo producto
            } else {
                alert("Error al registrar el producto: " + xhr.responseText);
            }
        } else {
            alert("Error al registrar el producto.");
        }
    };
    
    // Enviar la solicitud
    xhr.send(formData);
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

function mostrarRegistroProveedorModal() {
        document.getElementById('registerProviderModal').style.display = 'flex';
    }

    function cerrarRegistroProveedorModal() {
        document.getElementById('registerProviderModal').style.display = 'none';
    }

    function registrarProveedor() {
    // Crear objeto FormData a partir del formulario
    var formData = new FormData(document.getElementById("registerProviderForm"));

    // Configurar la solicitud AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "registrar_proveedor.php", true);

    // Manejar la respuesta
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText.trim() === "Proveedor registrado exitosamente.") {
                alert("Proveedor registrado exitosamente.");
                cerrarRegistroProveedorModal(); // Cerrar el modal después de registrar
            } else {
                alert("Error al registrar el proveedor: " + xhr.responseText);
            }
        } else {
            alert("Error al registrar el proveedor.");
        }
    };

    // Enviar la solicitud
    xhr.send(formData);
}

function searchMedicine() {
    var input = document.getElementById("searchInput").value.toLowerCase();
    var rows = document.querySelectorAll("tbody tr");
    rows.forEach(function(row) {
        var cells = row.querySelectorAll("td");
        var found = Array.from(cells).some(function(cell) {
            return cell.textContent.toLowerCase().includes(input);
        });
        row.style.display = found ? "" : "none";
    });
}


</script>

</body>
</html>
