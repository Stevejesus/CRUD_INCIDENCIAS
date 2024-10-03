<?php
    session_start();

    // Verifica si se ha enviado el color
    if (isset($_POST['color'])) {
        $_SESSION['backgroundcolor'] = $_POST['color']; // Guarda el color en la sesión
        echo json_encode(["status" => "success", "color" => $_SESSION['backgroundcolor']]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se recibió ningún color"]);
    }
?>