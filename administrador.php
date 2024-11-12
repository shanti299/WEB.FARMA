<?php
session_start();
if(isset($_SESSION["usuario"])){

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
	<style>
/* Estilo para el menú de navegación */

body {
background-image url ("img/salud-ciencia-medica-elegante_1419-2183.avif");
}
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
    margin: 0 10px;
}

nav img {
    height: 75px; /* Mantiene la imagen en su tamaño original */
}

.logout {
    color: white; /* Color del texto */
    text-decoration: none; /* Sin subrayado */
    font-weight: bold; /* Negrita para destacar */
    position: absolute; /* Coloca el enlace en una posición específica */
    right: 20px; /* Espaciado a la derecha */
    top: 30px; /* Espaciado hacia arriba */
    font-size: 16px; /* Ajustar el tamaño de la fuente si es necesario */
}

.logout:hover {
    text-decoration: underline; /* Subrayar al pasar el mouse */
}
</style>

</head>
<body>

    <nav>
	<img src="img/images.jpeg" alt="Bootstrap" width="75" height="75">
	<a href="salir.php">CERRAR SESION</a>
    </nav>
<br>

<center>

<div class="title-cards">
		<h2>Servicios que Ofrecemos</h2>
	</div>
<div class="container-card">
	
<div class="card">
	<figure>
		<img src="img/venta.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Nueva venta</h3>
		<p>.</p>
        <a href="nueva_venta.php">Nueva Venta</a>
	</div>
</div>

<div class="card">
	<figure>
		<img src="img/consu.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Consultar Medicamentico</h3>
		<p>.</p>
		<a href="consultar.php">Consultar Medicamento</a>
	</div>
</div>



<div class="card">
	<figure>
		<img src="img/movi.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Movimiento</h3>
		<p>.</p>
		<a href="movimiento.php">Movimiento</a>
	</div>
</div>

<div class="card">
	<figure>
		<img src="img/21bb57a4-8df9-4614-bad5-2b16e6f03c0a.jpg">
	</figure>
	<div class="contenido-card">
		<h3>Consultar Cliente</h3>
		<p>.</p>
		<a href="consultar_cliente.php">Consultar Cliente</a>
	</div>
</div>



<style>
    @import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
}
/*Cards*/
.container-card{
	width: 100%;
	display: flex;
	max-width: 1100px;
	margin: auto;
}
.title-cards{
	width: 100%;
	max-width: 1080px;
	margin: auto;
	padding: 20px;
	margin-top: 20px;
	text-align: center;
	color: #7a7a7a;
}
.card{
	width: 100%;
	margin: 20px;
	border-radius: 6px;
	overflow: hidden;
	background:#fff;
	box-shadow: 0px 1px 10px rgba(0,0,0,0.2);
	transition: all 400ms ease-out;
	cursor: default;
}
.card:hover{
	box-shadow: 5px 5px 20px rgba(0,0,0,0.4);
	transform: translateY(-3%);
}
.card img{
	width: 100%;
	height: 210px;
}
.card .contenido-card{
	padding: 15px;
	text-align: center;
}
.card .contenido-card h3{
	margin-bottom: 15px;
	color: #7a7a7a;
}
.card .contenido-card p{
	line-height: 1.8;
	color: #6a6a6a;
	font-size: 14px;
	margin-bottom: 5px;
}
.card .contenido-card a{
	display: inline-block;
	padding: 10px;
	margin-top: 10px;
	text-decoration: none;
	color: #2fb4cc;
	border: 1px solid #2fb4cc;
	border-radius: 4px;
	transition: all 400ms ease;
	margin-bottom: 5px;
}
.card .contenido-card a:hover{
	background: #2fb4cc;
	color: #fff;
}
@media only screen and (min-width:320px) and (max-width:768px){
	.container-card{
		flex-wrap: wrap;
	}
	.card{
		margin: 15px;
	}
}
</style>

</center>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php
}else{
    header("location:login.php");
}
?>