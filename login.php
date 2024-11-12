<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login de usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('https://www.rtanoticias.com/wp-content/uploads/2023/05/image-211.png');
            background-size: cover; /* Para asegurarte de que la imagen cubra todo el fondo */
            background-position: center; /* Para centrar la imagen */
            /* Otros estilos que desees para el fondo */
            font-family: Arial, helvetica, sans-serif;
        }
        form {
            border: 3px solid #f1f1f1;
            padding: 16px;
        }

    body {font-family: Arial, helvetica, sans-serif;}
    form {border: 3px solid #f1f1f1;padding: 16px;}
    
</style>

</head>
<body>
    
    
    <br>
    <br>
    <br><br><br>
    <center>
    <div class="card" style="width: 20rem;">
  <div class="card-body">
  <form action="login.php" method="POST">
        <H3>login de usuario</H3>
        <label for="txt1">Usuario:</label>
        <input type="" name="t1" required>
        <br>
        <br>
        <label for="txt1">Password:</label>
        <input type="password" name="t2" required>
        <br>
        <br>
        <input type="submit" name="" value="ingresar">

    
  </div>
  <?php
if($_POST){
    session_start();
    require('conexion.php');
    $u = $_POST['t1'];
    $p = $_POST['t2'];
    $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE nombre= :u AND contraseña = :p");
    $query->bindParam(":u", $u);
    $query->bindParam(":p", $p);  
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);
    if($usuario){
        $_SESSION['usuario'] = $usuario["nombre"];
        $_SESSION['role'] = $usuario["rol_id"];
        if ($usuario ["rol_id"] == "1"){
            header("location:administrador.php");

        }
        elseif ($usuario["rol_id"] == "2") {
            header("location:cliente.php");
        }
        
    }else{
        echo "Usuario o password son invalidos";
    }
}
?>
</div>
    </center>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>