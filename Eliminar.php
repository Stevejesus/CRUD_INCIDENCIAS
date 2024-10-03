<?php
    // Conexión con la base de datos
    $mysql = new mysqli("localhost", "root", "", "PHP-2");

    if($mysql->connect_error){
        die("Error de conexión: ".$mysql->connect_error);
    }

    // Verificar si se ha enviado el ID para eliminar
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Asegurarse de que sea un número entero

        // Consulta para eliminar el registro con el ID dado
        $sql = "DELETE FROM Incidencias WHERE id = $id";

        if ($mysql->query($sql)) {
            //$_SESSION['message'] = "Registro eliminado exitosamente.";
        } else {
            //$_SESSION['error_message'] = "Error al eliminar el registro: " . $mysql->error;
        }
    }

    // Redireccionar de vuelta a la página principal después de eliminar
    header("Location: Incidencias.php");
    exit(); // Asegurarse de detener el script después de la redirección
    $mysql->close(); // Cerrar la conexión  
?>
