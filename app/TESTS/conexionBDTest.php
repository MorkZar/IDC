<?php
namespace IDC\TESTS;

use PHPUnit\Framework\TestCase;
use IDC\ConexionBD;

require_once __DIR__ . '/../conexionBD.php';


class ConexionBDTest extends TestCase
{
    private $server = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "reservatec";
    private $port = 33065; 

    public function testConexionExitosa()
    {
        $db = new ConexionBD($this->server, $this->user, $this->pass, $this->db, $this->port);
        $this->assertTrue($db->estaConectado());


    }

    public function testConexionFallida()
    {
        $this->expectException(\Exception::class);

        // Intentamos conectar con un usuario, contraseña y DB incorrectos usando el mismo puerto
        new ConexionBD("localhost", "usuario_incorrecto", "123", "db_inexistente", $this->port);
    }
}

