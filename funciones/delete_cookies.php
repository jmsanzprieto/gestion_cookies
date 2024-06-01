<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_cookie'])) {
        $cookieNombre = $_POST['eliminar_cookie'];

        // Establece la fecha de expiración en el pasado para eliminar la cookie
        setcookie($cookieNombre, '', time() - 3600, '/');
        
        // Envía una respuesta indicando que la cookie fue eliminada
        echo json_encode(['status' => 'success', 'message' => 'Cookie eliminada']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nombre de cookie no especificado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>
