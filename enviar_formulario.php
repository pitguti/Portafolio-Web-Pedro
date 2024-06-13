<?php
// Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

$response = [];

// Depuración: verificar el método de solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    // Validar campos
    $errores = [];

    if (empty($nombre)) {
        $errores[] = "Por favor ingresa tu nombre.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Por favor ingresa un correo electrónico válido.";
    }

    if (!preg_match("/^[0-9]{7,15}$/", $telefono)) {
        $errores[] = "Por favor ingresa un número de teléfono válido.";
    }

    if (empty($asunto)) {
        $errores[] = "Por favor ingresa un asunto.";
    }

    if (empty($mensaje)) {
        $errores[] = "Por favor ingresa un mensaje.";
    }

    if (empty($errores)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp-mail.outlook.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pitguti07@outlook.com';
            $mail->Password = 'tenbuhourin07';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($mail->Username, 'Nuevo Mensaje de Contacto');
            $mail->addAddress('pitguti07@outlook.com', 'Ing. Pedro Gutiérrez Castillo');

            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asunto;

            $mail->Body = "Nombre: $nombre <br>Email: $email <br>Teléfono: $telefono <br>Asunto: $asunto <br><br>Mensaje: <br>$mensaje";
            $mail->AltBody = "Nombre: $nombre <br>Email: $email <br>Teléfono: $telefono <br>Asunto: $asunto <br><br>Mensaje: <br>$mensaje";

            if ($mail->send()) {
                $response = ['status' => 'success', 'message' => 'Gracias! ¡El mensaje ha sido enviado a mi correo!'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo];
            }
        } catch (Exception $e) {
            $response = ['status' => 'error', 'message' => 'Excepción al enviar el correo: ' . $e->getMessage()];
        }
    } else {
        $response = ['status' => 'error', 'message' => implode(", ", $errores)];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Formulario no enviado correctamente.'];
}

echo json_encode($response);
?>
