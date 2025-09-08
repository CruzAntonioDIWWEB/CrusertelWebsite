<?php

namespace Models;

use mysqli;

class Contact
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function save($nombre, $email, $asunto, $mensaje)
    {
        // Consulta para insertar en la tabla 'formularios'
        $sql = "INSERT INTO formularios (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if ($stmt === false) {
            die("Error en prepare(): " . $this->conexion->error);
        }

        $stmt->bind_param("ssss", $nombre, $email, $asunto, $mensaje);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}