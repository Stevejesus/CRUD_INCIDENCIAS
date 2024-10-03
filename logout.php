<?php
    session_start();
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruye la sesión

    header("Location: index.php"); // Redirigir al formulario de login
    exit();
?>