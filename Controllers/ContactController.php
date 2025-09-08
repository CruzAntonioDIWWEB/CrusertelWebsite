<?php

namespace Controllers;

use Models\Contact;

class ContactController extends BaseController
{
    public function showContactForm()
    {
        $this->loadView('contact/index');
    }

    public function submitContactForm()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"] ?? '';
            $email = $_POST["email"] ?? '';
            $asunto = $_POST["asunto"] ?? '';
            $mensaje = $_POST["mensaje"] ?? '';

            $contact = new Contact();
            $result = $contact->saveContact($nombre, $email, $asunto, $mensaje);

            if ($result) {
                echo "Mensaje guardado correctamente en la tabla formularios.";
                $this->sendEmailNotification($nombre, $email, $asunto, $mensaje);
            } else {
                echo "Error al guardar el mensaje.";
            }
        } else {
            echo "No se ha enviado el formulario correctamente.";
        }
    }

    private function sendEmailNotification($nombre, $email, $asunto, $mensaje)
    {
        $para = "info@crusertel.es";  
        $titulo = "Nuevo mensaje del formulario";
        $mensajeCorreo = "Has recibido un nuevo mensaje:\n\nNombre: $nombre\nEmail: $email\nAsunto: $asunto\nMensaje:\n$mensaje";
        $cabeceras = "From: noreply@crusertel.es"; 

        mail($para, $titulo, $mensajeCorreo, $cabeceras);
    }
}