<?php    
    session_start();

    // Verificar si hay un mensaje en la sesión
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        unset($_SESSION['mensaje']); // Borrar el mensaje después de mostrarlo
    }
    
    // Verificar si la variable de sesión del color de fondo está establecida
    if(!isset($_SESSION["backgroundcolor"])) {
        $_SESSION["backgroundcolor"] = "#FFFFFF"; // Color blanco por defecto
    }

    // Verificar si se ha enviado el formulario de cambio de color
    if(isset($_POST["Canviar"])) {
        $_SESSION["backgroundcolor"] = $_POST["color"];
    }

    // Verificar si se ha enviado el formulario de inicio de sesión
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];

        // Conexión con la base de datos
        $mysql = new mysqli("localhost", "root", "", "PHP-2");

        // Verificar la conexión
        if ($mysql->connect_error) {
            die("Error de conexión: " . $mysql->connect_error);
        }

        // Consultar la base de datos para verificar el usuario
        $stmt = $mysql->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc(); // Obtener el usuario

            // Verificar la contraseña
            if (password_verify($contrasena, $usuario['contrasena'])) {

                // Guardar el nombre del usuario en la sesión
                $_SESSION['nombres'] = $usuario['nombres']; // Guarda el nombre del usuario
                $_SESSION['id'] = $usuario['id']; // Guarda el ID del usuario

                // Establecer un mensaje de éxito
                $_SESSION['mensaje'] = "Inicio de sesión exitoso.";
            } else {
                // Contraseña incorrecta
                $_SESSION['mensaje'] = "Contraseña incorrecta.";
            }
        } else {
            // Usuario no encontrado
            $_SESSION['mensaje'] = "Usuario no encontrado.";
        }

    // Cerrar la conexión
    $stmt->close();
    $mysql->close();

    // Redirigir a index.php después de establecer el mensaje
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Fuentes de Google -->
    <link rel="stylesheet" href="Style_Login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            padding: 20px;
            border-radius: 8px; /* Bordes redondeados */
            background-color: rgba(255, 255, 255, 0.95); /* Fondo blanco semi-transparente */

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
        }

        .form-container h1 {
            margin-bottom: 20px;
        }
        
        form {
            margin-bottom: 20px; /* Espacio entre formularios */
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
        input[type="submit"] {
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
    </style>

    <!-- Imagen del Usuario -->
    <style> 
        .img{
            width: 200px;
            height: 200px;

            margin-bottom: 5px; /* Espacio debajo de la imagen */

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
            margin-bottom: 10px; /* Espacio debajo del selector de color */
        }
    </style>

    <!-- Estilos para la burbuja emergente -->
    <style>
        .mensaje-burbuja {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .mensaje-burbuja.success {
            background-color: #4CAF50;
        }

        .mensaje-burbuja.error {
            background-color: #f44336;
        }

        .mensaje-burbuja.mostrar {
            opacity: 1;
        }
    </style>

    <!-- Script de la "Paleta de colores" -->
    <script>
        function toggleColorPicker() {
            const colorInput = document.getElementById('color');
            colorInput.click(); // Simula el clic para abrir el selector de color
        }

        function updateColor() {
            const colorInput = document.getElementById('color');
            const colorValue = colorInput.value;
            const form = document.createElement('form');
            form.method = 'post';
            form.action = 'index.php';

            const colorField = document.createElement('input');
            colorField.type = 'hidden';
            colorField.name = 'color';
            colorField.value = colorValue;

            const submitField = document.createElement('input');
            submitField.type = 'hidden';
            submitField.name = 'Canviar';
            submitField.value = 'Canviar';

            form.appendChild(colorField);
            form.appendChild(submitField);
            document.body.appendChild(form);
            form.submit(); // Envía el formulario
        }
    </script>

    <!-- Script de SweetAlert -->
    <script>
        // Mostrar SweetAlert si hay un mensaje
        window.onload = function() {
            const mensaje = <?php echo json_encode(isset($mensaje) ? $mensaje : ''); ?>;
            if (mensaje) {
                const tipo = mensaje.includes('incorrecta') || mensaje.includes('no encontrado') ? 'error' : 'success';
                Swal.fire({
                    icon: tipo,
                    title: tipo === 'success' ? 'Éxito' : 'Error',
                    text: mensaje,
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (tipo === 'success') {
                        window.location.href = 'inicio.php';
                    }
                });
            }
        };
    </script>

</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;"> 

    <img src="imagenes/color.png" alt="Seleccionar color" class="color-button" onclick="toggleColorPicker()" />
    <input type="color" id="color" class="color-input" onchange="updateColor()">

    <div class="form-container">  
        <img src="imagenes/avatar.png" alt="user" class="img">

        <h1>Iniciar Sesión</h1>

        <form action="index.php" method="post" enctype="multipart/form-data"> 

            <label for="correo">Correo</label>
            <input type="email" name="correo" id="correo"><br>

            <label for="contrasena">Contraseña</label>
            <input type="password" name="contrasena" id="contrasena"><br>
    
            <input type="submit" name="submit" value="Iniciar Sesión">

        </form>

        <h1>Registrarse</h1>
        <form action="registrar.php" method="post" enctype="multipart/form-data"> 
            <input type="submit" name="submit" value="Crear una cuenta">
        </form>
    </div>

</body>