<?php
use PHPUnit\Framework\TestCase;

class ConexionTest extends TestCase
{
    private $server = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "reservatec";

    public function testConexionExitosa()
    {
        $conexion = new mysqli($this->server, $this->user, $this->pass, $this->db);

        // Verificar que no hay errores en la conexión
        $this->assertEquals(0, $conexion->connect_errno, "Error en la conexión: " . $conexion->connect_error);

        // Cerrar la conexión al finalizar
        $conexion->close();
    }
}
