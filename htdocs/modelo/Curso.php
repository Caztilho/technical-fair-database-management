<?php

require_once "Banco.php";

class Curso implements JsonSerializable {

    private $idCurso;
    private $nomeCurso;

    public function setIdCurso($idCurso){
        $this->idCurso = $idCurso;
    }

    public function getIdCurso(){
        return $this->idCurso;
    }

    public function setNomeCurso($nomeCurso){
        $this->nomeCurso = $nomeCurso;
    }

    public function getNomeCurso(){
        return $this->nomeCurso;
    }

    public function jsonSerialize()
    {
        $json = array();
        $json['idCurso'] = $this->getIdCurso();
        $json['nomeCurso'] = $this->getNomeCurso();

        return $json;
    }

    public function createCurso()
    {
        $this->banco = new Banco();
       
        $stmt = $this->banco->getConexao()->prepare("insert into curso(idCurso, nomeCurso) values (?,?)");
        $stmt->bind_param("is", $this->idCurso, $this->nomeCurso);
        $resposta = $stmt->execute();
        
        return $resposta;
    }

    public function cursoExiste(){
        $this->banco = new Banco(); 
        $sql = "select count(*) as qtd from curso where idCurso = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        
        $stmt->bind_param("i", $this->idCurso);
        $stmt->execute();  
        $resultado = $stmt->get_result();
        
        while ($linha = $resultado->fetch_object()) {
            if ($linha->qtd == 1) {
                return true;
            }
        }

        return false;
    }

    public function read(){
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from curso where idCurso=?");
        
        $stmt->bind_param("i", $this->idCurso);
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        
        $curso = array();
        while ($linha = $resultado->fetch_object()) {
            $curso[0] = new Curso();
            $curso[0]->setIdCurso($linha->idCurso);
            $curso[0]->setNomeCurso($linha->nomeCurso);
        }

        return $curso; 
    }
    
    
    public function readAll(){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from curso");
        $stmt->execute();  
        $resultado = $stmt->get_result();  
        
        $curso = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()) {
            $curso[$i] = new Curso(); 
            
            $curso[$i]->setIdCurso($linha->idCurso);
            $curso[$i]->setNomeCurso($linha->nomeCurso);
            $i++;
        }

        return $curso; 
    }

    public function update(){   
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("update curso set nomeCurso = ? where idCurso = ?");
        $stmt->bind_param("si", $this->nomeCurso, $this->idCurso);

        return $stmt->execute(); 
    }

    public function delete()
    {
        $this->banco = new Banco();  
        
        $stmt = $this->banco->getConexao()->prepare("delete from curso where idCurso = ?");
        $stmt->bind_param("i", $this->idCurso);
        
        return $stmt->execute(); 
    }
}

?>