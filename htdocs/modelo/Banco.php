<?php
class Banco
{
    private $host = "127.0.0.1";
    private $usuario = "root";
    private $senha = "";
    private $banco = "feiratecnica";
    private $porta = "3306";
    private $con = null;

    private function conectar(){
        $this->con = new mysqli($this->host, $this->usuario, $this->senha, $this->banco, $this->porta);
        if ($this->con->connect_error) {
            $resposta['erro'] = $this->con->connect_error;
            echo json_encode($resposta['erro']);
            die();
        }
    }
    public function getConexao(){
        if ($this->con == null) {
            $this->conectar();
        }
        return $this->con;
    }
}
