<?php
    session_start(); // Inicia la sesión

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <!-- General y Contenedor -->
    <style> 
        *{
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            font-family: 'Poppins', 'sans-serif';
        }

        body{
            height: 100vh;

            flex-direction: column; /* Para apilar elementos verticalmente */
            justify-content: center; /* Centrar el contenido verticalmente */
            align-items: center; /* Centrar el contenido horizontalmente */
            display: flex;
            background-color: <?php echo $_SESSION['backgroundcolor']; ?>;
        }

        .form-container {
            width: 500px; /* Ancho mínimo */
            min-height: 650px; /* Alto mínimo */
            margin: auto;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            border-radius: 8px; /* Bordes redondeados */
            background-color: rgba(255, 255, 255, 0.95); /* Fondo blanco semi-transparente */

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        .form-container h1 {
            margin-bottom: 20px;
        }

        label {
            display: block; /* Cada etiqueta en una nueva línea */
            margin-bottom: 5px; /* Espacio debajo de cada etiqueta */
        }
    </style>

    <!-- Botones "Submit" -->
    <style> 
        input[type="email"],
        input[type="password"],
        input[type="color"],
        input[type="submit"],
        input[type="text"] {
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 10px; /* Espacio interno en los inputs */
            margin-bottom: 15px; /* Espacio entre inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */
        }

        input[type="submit"] {
            background-color: #007BFF; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre el botón */
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Color de fondo al pasar el mouse */
        }

        .color-input {
            display: none; /* Oculto inicialmente */
            margin-bottom: 10px; /* Espacio debajo del selector de color */
        }
    </style>

    <!-- Botón de Volver -->
    <style> 
        .volver-button {
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre la imagen */
            position: absolute; /* Posiciona el botón fuera del contenedor */
            top: 20px; /* Ajusta la posición vertical */
            left: 20px; /* Ajusta la posición horizontal */

            width: auto; /* Ancho de la imagen */
            height: auto; /* Alto de la imagen */

            padding: 10px; /* Espacio interno en los inputs */
            margin-bottom: 15px; /* Espacio entre inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */

            background-color: white; /* Color de fondo del botón */
            color: black; /* Color del texto del botón */
            cursor: pointer;
        }
        .volver-button:hover {
            background-color: black; /* Color de fondo al pasar el mouse */
            color: white; /* Cambia el color del texto al pasar el mouse */
        }
    </style>

    <!-- Imagen del Usuario -->
    <style> 
        .img{
            width: 150px;
            height: 150px;

            margin-bottom: 10px; /* Espacio debajo de la imagen */
        }
    </style>

</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;">  
    <button class="volver-button" onclick="window.location.href='index.php'">&lt; Volver</button>

    <div class="form-container"> 
        <img src="imagenes/agregar-usuario.png" alt="user" class="img">

        <h1>Registro</h1>
        <form action="guardar.php" method="post" enctype="multipart/form-data" onsubmit="return validarFormulario();"> 
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" required><br>

            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" id="apellidos" required><br>

            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo" required><br>

            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena" required><br>

            <input type="submit" name="submit" value="Guardar">
        </form>

    </div>
</body>
</html>