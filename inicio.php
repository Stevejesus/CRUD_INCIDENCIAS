<?php
    session_start();

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }

    // Verificar si el nombre del usuario está en la sesión
    if (!isset($_SESSION['nombres'])) {
        // Redirigir al formulario de login o mostrar mensaje si no ha iniciado sesión
        header("Location: index.php"); // O a la página que desees
        exit();
    }
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <!-- Fuentes de Google -->
    <link rel="stylesheet" href="Style_Login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Funcion de Notificacion -->
    <script>
        function confirmLogout() {
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Se cerrará tu sesión.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí",
                cancelButtonText: "No",
                dangerMode: true,
                backdrop: true, // Mantiene el fondo oscuro
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a logout.php si se confirma la salida
                    window.location.href = 'logout.php';
                    Swal.close(); // Cerrar la alerta
                }
            });
        }
    </script> 

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
            min-width: 500px; /* Ancho mínimo */
            min-height: 500px; /* Alto mínimo */
            margin: auto;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 8px; /* Bordes redondeados */
            background-color: rgba(255, 255, 255, 0.95); /* Fondo blanco semi-transparente */

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block; /* Cada etiqueta en una nueva línea */
            margin-bottom: 5px; /* Espacio debajo de cada etiqueta */
        }
    </style>

    <!-- Botones "Submit" -->
    <style> 
        input[type="submit"]{
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 10px; /* Espacio interno en los inputs */
            margin-top: 20px; /* Espacio entre inputs */
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

    <!-- Imagen del Usuario -->
    <style> 
        .img{
            width: 250px;
            height: 250px;

            margin-bottom: 20px; /* Espacio debajo de la imagen */
        }
    </style>

    <!-- Botón de Volver -->
    <style> 
        .volver-button {
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre la imagen */
            position: absolute; /* Posiciona el botón fuera del contenedor */
            top: 20px; /* Ajusta la posición vertical */
            right: 20px; /* Ajusta la posición horizontal */

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

        .color-input {
            display: none; /* Oculto inicialmente */
            margin-bottom: 10px; /* Espacio debajo del selector de color */
        }
    </style>

    <!-- Boton del Color -->
    <style>
        .color-button {
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre la imagen */
            position: absolute; /* Posiciona el botón fuera del contenedor */
            top: 20px; /* Ajusta la posición vertical */
            left: 20px; /* Ajusta la posición horizontal */

            width: 35px; /* Ancho de la imagen */
            height: 35px; /* Alto de la imagen */

            transition: transform 0.2s; /* Agrega una transición suave al escalar */
        }

        .color-button:hover {
            transform: scale(1.3); /* Escalar la imagen al pasar el mouse */
        }

        .color-input {
            display: none; /* Oculto inicialmente */
        }
    </style>

</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;"> 
    
    <img src="imagenes/color.png" alt="Seleccionar color" class="color-button" onclick="toggleColorPicker()" />
    <input type="color" id="color" class="color-input" onchange="updateColor()">

    <button class="volver-button" onclick="confirmLogout()">&lt; Cerrar Sesión</button>

    <div class="form-container">
        <img src="imagenes/usuario (2).png" alt="user" class="img">

        <h1 class="welcome">¡Bienvenido <?php echo htmlspecialchars($_SESSION['nombres']); ?>!</h1> <!-- Mostrar el nombre del usuario -->

        <form action="Incidencias.php" method="post" enctype="multipart/form-data"> 
            <input type="submit" name="submit" value="Ver Incidencias">
        </form>

        <form action="AgregarIncidencia.php" method="post" enctype="multipart/form-data"> 
            <input type="submit" name="submit" value="Agregar Incidencia">
        </form>

    </div>
    
    <!-- Script de la "Paleta de colores" -->
    <script>
        function toggleColorPicker() {
            const colorInput = document.getElementById('color');
            colorInput.click(); // Simula el clic para abrir el selector de color
        }

        function updateColor() {
            const colorInput = document.getElementById('color');
            const colorValue = colorInput.value;

            // Realizar la solicitud AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_color.php", true); // Archivo PHP para manejar el cambio de color
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Cambiar el color de fondo del cuerpo sin redirigir
                    document.body.style.backgroundColor = colorValue;
                } else {
                    console.error("Error al actualizar el color");
                }
            };

            xhr.send("color=" + encodeURIComponent(colorValue)); // Envía el color al servidor
        }
    </script>

</body>
</html>