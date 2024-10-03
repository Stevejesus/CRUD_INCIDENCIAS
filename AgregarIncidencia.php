<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    session_start();

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }

    // Verificar que el usuario ha iniciado sesión
    if (!isset($_SESSION['id'])) {
        die("Acceso denegado. Debes iniciar sesión.");
    }

    // Inicializar mensaje en sesión
    if (!isset($_SESSION['mensaje'])) {
        $_SESSION['mensaje'] = null; // Asegurarse de que exista
    }

    // Conexión a la base de datos
    $mysql = new mysqli("localhost", "root", "", "PHP-2");

    // Verificar la conexión
    if ($mysql->connect_error) {
        die('Problemas con la conexión a la base de datos');
    }
    
    // Verificar si el formulario ha sido enviado
    if (isset($_POST["submit"])) {
        $usuario = $_SESSION['id']; // ID del usuario que ha iniciado sesión
        
        // Verifica si los campos están establecidos
        $nombre = isset($_POST["nombre"]) ? htmlspecialchars(trim($_POST["nombre"])) : '';
        $fecha = isset($_POST["fecha"]) ? htmlspecialchars(trim($_POST["fecha"])) : '';
        $prioridad = isset($_POST["prioridad"]) ? htmlspecialchars(trim($_POST["prioridad"])) : '';
        $descripcion = isset($_POST["descripcion"]) ? htmlspecialchars(trim($_POST["descripcion"])) : '';

        // Verificar que los campos no estén vacíos
        if (!empty($nombre) || !empty($prioridad) || !empty($descripcion) || !empty($fecha)) {
            // Insertar la incidencia en la base de datos
            $query = "INSERT INTO Incidencias (nombre, prioridad, descripcion, usuario, fecha) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysql->prepare($query);

            // Verificar si la preparación de la declaración fue exitosa
            if ($stmt === false) {
                die("Error en la preparación de la declaración: " . $mysql->error);
            }

            // Vincular los parámetros
            $stmt->bind_param("sssis", $nombre, $prioridad, $descripcion, $usuario, $fecha);

            // Ejecutar la declaración
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Incidencia registrada exitosamente.";
                $_SESSION['mensaje_clase'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al registrar la incidencia: " . $stmt->error;;
                $_SESSION['mensaje_clase'] = "error";
            }

            // Cerrar la declaración
            $stmt->close();
        }
        
    }

    $mysql->close(); 
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Incidencia</title>

    <!-- Fuentes de Google -->
    <link rel="stylesheet" href="Style_Login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Funcion de Notificacion -->
    <script>
        function showAlert(title, message, icon) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonText: 'Aceptar'
            });
        }

        // Mostrar alerta si hay mensaje
        window.onload = function() {
            <?php
            if (isset($_SESSION['mensaje']) && !empty($_SESSION['mensaje'])) {
                $mensaje = addslashes($_SESSION['mensaje']); // Escapar mensaje
                $clase = $_SESSION['mensaje_clase'];

                // Determinar el título e icono basado en la clase del mensaje
                if ($clase == "success") {
                    echo "showAlert('Éxito', '$mensaje', 'success');";
                } else if ($clase == "warning") {
                    echo "showAlert('Advertencia', '$mensaje', 'warning');"; // Mensaje de advertencia
                } else {
                    echo "showAlert('Error', '$mensaje', 'error');";
                }

                // Limpiar el mensaje y la clase después de mostrar
                unset($_SESSION['mensaje']); // Limpiar después de mostrar
                unset($_SESSION['mensaje_clase']); // Limpiar la clase también
            }
            ?>
        };
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
            min-width: auto; /* Ancho mínimo */
            min-height: auto; /* Alto mínimo */
            margin: auto;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px;
            border-radius: 8px; /* Bordes redondeados */
            background-color: rgba(255, 255, 255, 0.95); /* Fondo blanco semi-transparente */

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        .form-container h1{
            font-size: 2.5rem;
        }
    </style>

    <!-- Botones "Submit" -->
    <style> 
        input[type="submit"]{
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            
            padding: 10px; /* Espacio interno en los inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */

            margin-top: 30px;
        
            background-color: #28a745; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre el botón */
        }

        input[type="submit"]:hover {
            background-color: #218838; /* Color de fondo al pasar el mouse */
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

        .color-input {
            display: none; /* Oculto inicialmente */
            margin-bottom: 10px; /* Espacio debajo del selector de color */
        }
    </style>

    <!-- Types [text, date, prioridad, descripcion] -->
    <style> 
        label {
            display: block; /* Cada etiqueta en una nueva línea */
            margin-top: 20px;
        }

        input[type="text"],
        input[type="date"],
        select[type="prioridad"] {
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 10px; /* Espacio interno en los inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */
        }

        textarea[type="descripcion"] {
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            height: 200px;

            padding: 10px; /* Espacio interno en los inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */
        }

    </style>

    <style> 

        input[value="Ver Incidencias"] {
            background-color: #007BFF; /* Color de fondo del botón */
        }
        
        input[value="Ver Incidencias"]:hover {
            background-color: #0056b3; /* Color de fondo al pasar el mouse */
        }
    </style>

</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;"> 
    <button class="volver-button" onclick="window.location.href='inicio.php'">&lt; Volver</button>

    <div class="form-container"> 
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Información de la Incidencia</h1>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="prioridad">Prioridad:</label>
            <select type="prioridad" id="prioridad" name="prioridad" required>
                <option value="Baja">Baja</option>    
                <option value="Media">Media</option>
                <option value="Alta">Alta</option>
            </select><br>

            <label for="descripcion">Descripción:</label>
            <textarea type="descripcion" id="descripcion" name="descripcion" required></textarea><br>

            <input type="submit" name="submit" value="Agregar">
        </form>

        <form action="Incidencias.php" method="post" enctype="multipart/form-data"> 
            <input type="submit" name="submit" value="Ver Incidencias">
        </form>
    </div>    

</body>
</html>