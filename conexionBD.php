<?php
namespace IDC;

class ConexionBD
{
    private $server;
    private $user;
    private $pass;
    private $db;
    private $port;
    public $conexion;

    public function __construct(
        $server = "localhost",
        $user = "root",
        $pass = "",
        $db = "reservatec",
        $port = 33065 // <--- Puerto
    )
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        $this->port = $port;

        
        $this->conexion = new \mysqli(
            $this->server,
            $this->user,
            $this->pass,
            $this->db,
            $this->port
        );

        if ($this->conexion->connect_error) {
            throw new \Exception("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function estaConectado()
    {
        return $this->conexion->ping();
    }
}
