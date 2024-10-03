<?php
    session_start();

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }

    // Verificar que el usuario ha iniciado sesión
    if (!isset($_SESSION['id'])) {
        die("Acceso denegado. Debes iniciar sesión.");
    }

    // Mostrar mensajes de éxito o error
    if (isset($_SESSION['mensaje'])) {
        echo '<div style="color: green;">' . $_SESSION['mensaje'] . '</div>';
        unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
    }

    if (isset($_SESSION['error_message'])) {
        echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']); // Limpiar el mensaje de error después de mostrarlo
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Incidencias</title>

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
            min-height: 100vh;

            flex-direction: column; /* Para apilar elementos verticalmente */
            justify-content: center; /* Centrar el contenido verticalmente */
            align-items: center; /* Centrar el contenido horizontalmente */
            display: flex;
            background-color: <?php echo $_SESSION['backgroundcolor']; ?>;
        }

        .form-container {
            min-width: 80%; /* Ancho mínimo */
            max-width: 800px; /* Establece un ancho máximo */

            margin: 50px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border-radius: 8px; /* Bordes redondeados */
            background-color: rgba(255, 255, 255, 0.95); /* Fondo blanco semi-transparente */

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        .form-container h1{
            font-size: 3rem;
            margin-bottom: 15px
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
            margin-top: 40px; /* Espacio entre inputs */
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

    <!-- Tabla -->
    <style> 
        table[type="tabla"]{
            width: 100%;
            height: auto;

            background-color: white;
            padding: auto;

            margin-top: 0px; /* Ajusta este valor según tus necesidades */
        }

        table[type="tabla"] {
            width: auto;
            height: auto;
            background-color: white;
            padding: auto;
            margin-top: 20px; /* Cambié este valor a 20px para asegurar que haya un espacio adecuado */
            border-collapse: collapse; /* Para evitar espacio adicional entre las celdas */
        }

        /* Asegúrate de que las celdas tengan un poco de espacio */
        table[type="tabla"] th, 
        table[type="tabla"] td {
            padding: 5px; /* Espacio interno en las celdas */
            border: 1px solid #ccc; /* Borde de las celdas */
        } 
    </style>
    
    <!-- Botones "Eliminar" y "Editar" -->
    <style>
        button[type="eliminar"]{
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 5px; /* Espacio interno en los inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */

            margin-bottom: 5px;
        }

        button[type="eliminar"] {
            background-color: #FF0000; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre el botón */
        }

        button[type="eliminar"]:hover {
            background-color: #cc0000; /* Color de fondo al pasar el mouse */
        }

        button[type="editar"]{
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 5px; /* Espacio interno en los inputs */
            border: 1px solid #ccc; /* Color del borde */
            border-radius: 5px; /* Bordes redondeados */

            margin-top: 5px;
            margin-bottom: 5px;
        }

        button[type="editar"] {
            background-color: #FFBF00; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            cursor: pointer; /* Cambiar el cursor al pasar el mouse sobre el botón */
        }

        button[type="editar"]:hover {
            background-color: #E6AC00; /* Color de fondo al pasar el mouse */
        }
    </style>

    <!-- Estilo de la celda de descripción -->
    <style>
        /* Agrega esta clase para limitar el ancho de las celdas de la descripción */
        .descripcion-cell {
            max-width: 550px; /* Cambia este valor según tus necesidades */
            overflow: hidden; /* Oculta el desbordamiento */
            text-overflow: ellipsis; /* Muestra '...' para texto desbordado */
            white-space: normal; /* Permite que el texto fluya a la siguiente línea */
            word-wrap: break-word; /* Permite el salto de palabra si es necesario */
        }

        .text-center {
            text-align: center; /* Centra el texto horizontalmente */
        }

    </style>
</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;"> 
    <button class="volver-button" onclick="window.location.href='inicio.php'">&lt; Volver</button>

    <div class="form-container"> 
        <h1>Incidencias</h1>

        <table type="tabla"> 
            <tr> 
                <!--
                <th>ID</th>
                <th>ID Usuario</th>
                -->

                <th>Nombre</th>
                <th>Fecha</th>
                <th>Prioridad</th>
                <th>Descripción</th>

                <th>Opciones</th>
            </tr>

            <?php
                $mysql = new mysqli("localhost", "root", "", "PHP-2"); //Conexion con la base de datos

                if($mysql->connect_error){
                    die("Error de conexión: ".$mysql->connect_error);
                }

                $incidencia = $mysql->query("select * from Incidencias") or 
                die($mysql->error); //Consulta a la base de datos

                while ($inc = $incidencia->fetch_array()){ //Recorre la base de datos
                    echo "<tr>";

                    //echo "<td>".$inc['id']."</td>";
                    //echo "<td>".$inc['usuario']."</td>";

                    echo "<td>".$inc['nombre']."</td>";
                    echo "<td class='text-center'>" . $inc['fecha'] . "</td>"; 
                    echo "<td class='text-center'>" . $inc['prioridad'] . "</td>";
                    echo "<td class='descripcion-cell'>".$inc['descripcion']."</td>"; 

                    if ($_SESSION['id'] == $inc['usuario']) {
                        echo "<td>";
                        echo '<form action="editar.php" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="'.$inc['id'].'">
                                <button type="editar">Editar</button>
                              </form><br>';
                              
                        echo '<form action="eliminar.php" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="'.$inc['id'].'">
                                <button type="eliminar">Eliminar</button>
                              </form>';
                        echo "</td>";
                    }

                    echo "<tr>";
                }
                $mysql->close();
            ?>
        </table>

        <form action="AgregarIncidencia.php" method="post" enctype="multipart/form-data"> 
            <input type="submit" name="submit" value="Agregar Incidencia">
        </form>

    </div>
    
</body>

</html>