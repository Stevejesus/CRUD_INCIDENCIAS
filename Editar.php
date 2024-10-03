<?php
    session_start(); // Iniciar la sesión

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }
    // Conexión con la base de datos
    $mysql = new mysqli("localhost", "root", "", "PHP-2");

    if($mysql->connect_error){
        die("Error de conexión: ".$mysql->connect_error);
    }

    // Verificar si se ha recibido el ID a través de GET para editar
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Convertir a entero

        // Consulta para obtener los datos de la Incidencia
        $result = $mysql->query("SELECT * FROM Incidencias WHERE id = $id");
    
        if ($result->num_rows > 0) {
            $incidencia = $result->fetch_assoc(); // Obtener los datos de la Incidencia 
            // Convertir la fecha al formato adecuado si es necesario
            $fecha_formateada = date('Y-m-d', strtotime($incidencia['fecha']));
        } else {
            die("Incidencia no encontrado.");
        }   
    }
    else {
        die("ID de Incidencia no proporcionado.");
    }

    // Verificar si se envió el formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $prioridad = $_POST['prioridad'];
        $descripcion = $_POST['descripcion'];
        $fecha = $_POST['fecha'];

        // Actualizar los datos en la base de datos
        $sql = "UPDATE Incidencias SET nombre = '$nombre', prioridad = '$prioridad', fecha = '$fecha', descripcion = '$descripcion' WHERE id = $id";

        if ($mysql->query($sql)) {
            //$_SESSION['mensaje'] = "Incidencia actualizado correctamente.";
            //$_SESSION['mensaje_clase'] = "success"; 
            header("Location: Incidencias.php");
            exit();
        } else {
            //$_SESSION['mensaje'] = "Error al actualizar la incidencia: " . $mysql->error;
            //$_SESSION['mensaje_clase'] = "error"; 
            header("Location: Incidencias.php");
            exit();
        }
    }

    $mysql->close(); // Cerrar la conexión
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Incidencia</title>

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
            font-size: 3rem;
            margin-bottom: 15px
        }

        .form-container h1{
            font-size: 3rem;
        }
    </style>

    <!-- Botones "Submit" -->
    <style> 
        input[type="submit"]{
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 10px; /* Espacio interno en los inputs */
            margin-top: 40px; /* Espacio entre inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */
        }

        input[type="submit"] {
            background-color:#FFBF00; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre el botón */
        }

        input[type="submit"]:hover {
            background-color: #E6AC00; /* Color de fondo al pasar el mouse */
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
</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;">
    <button class="volver-button" onclick="window.location.href='Incidencias.php'">&lt; Volver</button>

    <div class="form-container"> 
        <form method="post">
            <h1>Editar Incidencia</h1>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $incidencia['nombre']; ?>" required>

            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha_formateada; ?>" required>

            <label for="prioridad">Prioridad:</label>
            <select type="prioridad" id="prioridad" name="prioridad" required>
                <option value="Baja" <?php if ($incidencia['prioridad'] == 'Baja') echo 'selected'; ?>>Baja</option>    
                <option value="Media" <?php if ($incidencia['prioridad'] == 'Media') echo 'selected'; ?>>Media</option>
                <option value="Alta" <?php if ($incidencia['prioridad'] == 'Alta') echo 'selected'; ?>>Alta</option>
            </select>

            <label for="descripcion">Descripción:</label>
            <textarea type="descripcion" id="descripcion" name="descripcion" required><?php echo $incidencia['descripcion']; ?></textarea>

            <input type="submit" name="submit" value="Actualizar">
        </form>
    </div>

</body>
</html>