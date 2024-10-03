<?php
    session_start(); // Iniciar la sesión

    // Verificar si la variable de sesión del color de fondo está establecida
    if (!isset($_SESSION['backgroundcolor'])) {
        $_SESSION['backgroundcolor'] = '#FFFFFF'; // Color por defecto si no está definido
    }

    if (isset($_POST["submit"])) {
        // Limpiar los datos recibidos para evitar inyecciones SQL
        $nombres = htmlspecialchars(trim($_POST["nombres"])); 
        $apellidos = htmlspecialchars(trim($_POST["apellidos"]));
        $correo = htmlspecialchars(trim($_POST["correo"]));
        $password = $_POST["contrasena"];
        $hash = password_hash($password, PASSWORD_DEFAULT); // Hashear la contraseña

        $mysql = new mysqli("localhost", "root", "", "PHP-2");

        // Verificar la conexión
        if ($mysql->connect_error) {
            die('Problemas con la conexion a la base de datos');
        }

        // Verificar si el correo ya está registrado
        $checkQuery = "SELECT * FROM usuarios WHERE correo = ?";
        $checkStmt = $mysql->prepare($checkQuery);
        $checkStmt->bind_param("s", $correo);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            // Si el correo ya está registrado, mostrar un mensaje de error
            echo "<script>alert('El correo ya está registrado.'); window.location.href='registrar.php';</script>";
        } else {
            // Si el correo no está registrado, proceder con el registro
            $query = "INSERT INTO usuarios (nombres, apellidos, correo, contrasena) VALUES (?, ?, ?, ?)";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("ssss", $nombres, $apellidos, $correo, $hash);

            if ($stmt->execute()) {
                // Obtener el ID del usuario registrado
                $userIdQuery = "SELECT id FROM usuarios WHERE correo = ?";
                $userIdStmt = $mysql->prepare($userIdQuery);
                $userIdStmt->bind_param("s", $correo);
                $userIdStmt->execute();
                $userIdResult = $userIdStmt->get_result();

                if ($userIdResult->num_rows > 0) {
                    $userIdRow = $userIdResult->fetch_assoc();
                    $_SESSION['id'] = $userIdRow['id']; // Guardar el ID del usuario en la sesión
                }

                // Guardar los datos en la sesión y redirigir
                $_SESSION['nombres'] = $nombres;
                $_SESSION['apellidos'] = $apellidos;
                $_SESSION['correo'] = $correo;
            } else {
                die("Error al registrar: " . $stmt->error);
            }

            $stmt->close();
            $userIdStmt->close();
        }
        $checkStmt->close();
        $mysql->close();
    }
?> 

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Registro</title>

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
        .color-input {
            display: none; /* Oculto inicialmente */
            margin-bottom: 10px; /* Espacio debajo del selector de color */
        }

        input[type="submit"] {
            width: 100%; /* Para que todos los inputs ocupen el ancho completo */
            padding: 10px; /* Espacio interno en los inputs */
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

    <!-- Texto h2 del Contenedor -->
    <style> 
        .form-container h2 {
            margin-top:20px;
            font-size: 1.5em; /* Tamaño del subtítulo */ 
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

    <!-- Emoji -->
    <style> 
        .img {
            font-size: 6em; /* Tamaño del título */
            margin-bottom: 10px; /* Espacio debajo de la imagen */
        }
    </style>
</head>

<body style="background-color: <?php echo $_SESSION['backgroundcolor']; ?>;"> 
    <button class="volver-button" onclick="window.location.href='index.php'">&lt; Volver</button>

    <div class="form-container">
        
        <img src="imagenes/hola.jpg" alt="user" class="img">

        <h1>¡Hola <?php echo htmlspecialchars($_SESSION['nombres']); ?>!</h1> <!-- Mostrar el nombre del usuario -->
        
        <h2>Datos Registrados</h2>
        <?php
        // Mostrar el contenido de la sesión para depuración
        if (isset($_SESSION['nombres'])) {
            echo "<p><strong>Nombres:</strong> " . htmlspecialchars($_SESSION['nombres']) . "</p>";
            echo "<p><strong>Apellidos:</strong> " . htmlspecialchars($_SESSION['apellidos']) . "</p>";
            echo "<p><strong>Correo:</strong> " . htmlspecialchars($_SESSION['correo']) . "</p>";
        } else {
            echo "<p>No hay datos registrados.</p>";}
        ?>
        <h2></h2>
        <form action="inicio.php" method="post">
            <input type="submit" value="Comenzar">
        </form>
    </div>
    
</body>
</html>