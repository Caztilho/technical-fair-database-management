<?php

require_once 'Banco.php';

class Avaliacao implements JsonSerializable {
    
    private $idAvaliacao;
    private $professor_registro;
    private $notaGeral;
    private $obs;
    private $trabalho_idTrabalho;

    public function getIdAvaliacao()
    {
        return $this->idAvaliacao;
    }

    public function setIdAvaliacao($idAvaliacao)
    {
        $this->idAvaliacao = $idAvaliacao;
        return $this;
    }

    public function getProfessor_registro()
    {
        return $this->professor_registro;
    }

    public function setProfessor_registro($professor_registro)
    {
        $this->professor_registro = $professor_registro;
        return $this;
    }

    public function getNotaGeral()
    {
        return $this->notaGeral;
    }

    public function setNotaGeral($notaGeral)
    {
        $this->notaGeral = $notaGeral;
        return $this;
    }

    public function getObs()
    {
        return $this->obs;
    }

    public function setObs($obs)
    {
        $this->obs = $obs;
        return $this;
    }
    
    public function getTrabalho_idTrabalho()
    {
        return $this->trabalho_idTrabalho;
    }

    public function setTrabalho_idTrabalho($trabalho_idTrabalho)
    {
        $this->trabalho_idTrabalho = $trabalho_idTrabalho;
        return $this;
    }

    public function jsonSerialize()
    {
        $json = array();
        $json['idAvaliacao'] = $this->getIdAvaliacao();
        $json['professor_registro'] = $this->getProfessor_registro();
        $json['notaGeral'] = $this->getNotaGeral();
        $json['obs'] = $this->getObs();
        $json['trabalho_idTrabalho'] = $this->getTrabalho_idTrabalho();
        return $json;
    }

    public function createAvaliacao()
    {
        $this->banco = new Banco();
        
        $stmt = $this->banco->getConexao()->prepare("insert into avaliacao (idAvaliacao, professor_registro, notaGeral, obs, Trabalho_idTrabalho) values (?,?,?,?,?)");
        $stmt->bind_param("iiisi", $this->idAvaliacao, $this->professor_registro, $this->notaGeral, $this->obs, $this->trabalho_idTrabalho);
        $resposta = $stmt->execute();
        
        return $resposta;
    }

    public function avaliacaoExiste(){
        $this->banco = new Banco();  
        $sql = "select count(*) as qtd from avaliacao where idavaliacao = ? or (professor_registro = ? and trabalho_idtrabalho = ?)";
        $stmt = $this->banco->getConexao()->prepare($sql);
        
        $stmt->bind_param("isi", $this->idAvaliacao, $this->professor_registro, $this->trabalho_idTrabalho);
        $stmt->execute();  
        $resultado = $stmt->get_result();
        
        while ($linha = $resultado->fetch_object()) {
            if ($linha->qtd == 1) {
                return true;
            }
        }

        return false;
    }

    public function delete()
    {
        $this->banco = new Banco();  
        $stmt = $this->banco->getConexao()->prepare("delete from avaliacao where idAvaliacao = ?");
        
        $stmt->bind_param("i", $this->idAvaliacao);
        
        return $stmt->execute(); 
    }

    public function update(){   
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("update avaliacao set professor_registro=?, notaGeral=?, obs=?, Trabalho_idTrabalho=? where idAvaliacao = ?");
        
        $stmt->bind_param("iisii", $this->professor_registro, $this->notaGeral, $this->obs, $this->trabalho_idTrabalho, $this->idAvaliacao);
        return $stmt->execute();  
    }

    public function read()
    {
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from avaliacao where idAvaliacao=?");
        
        $stmt->bind_param("i", $this->idAvaliacao);
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        
        $avaliacao= array();
        while ($linha = $resultado->fetch_object()) {
            $avaliacao[0] = new Avaliacao();
            $avaliacao[0]->setIdAvaliacao($linha->idAvaliacao);
            $avaliacao[0]->setProfessor_registro($linha->professor_registro);
            $avaliacao[0]->setNotaGeral($linha->notaGeral);
            $avaliacao[0]->setObs($linha->obs);
            $avaliacao[0]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
        }
        return $avaliacao; 
    }

    public function readAll()
    { 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from avaliacao");
        $stmt->execute();  
        $resultado = $stmt->get_result();  
        $avaliacao = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()) {
            $avaliacao[$i] = new Avaliacao(); 
        
            $avaliacao[$i]->setIdAvaliacao($linha->idAvaliacao);
            $avaliacao[$i]->setProfessor_registro($linha->professor_registro);
            $avaliacao[$i]->setNotaGeral($linha->notaGeral);
            $avaliacao[$i]->setObs($linha->obs);
            $avaliacao[$i]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
            $i++;
        }

        return $avaliacao; 
    }

    public function readAllWithComent()
    { 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare('select * from avaliacao where obs is not null and obs <> "" and professor_registro is not null');
        $stmt->execute();  
        $resultado = $stmt->get_result();  
        $avaliacao = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()) {
            $avaliacao[$i] = new Avaliacao(); 
            
            $avaliacao[$i]->setIdAvaliacao($linha->idAvaliacao);
            $avaliacao[$i]->setProfessor_registro($linha->professor_registro);
            $avaliacao[$i]->setNotaGeral($linha->notaGeral);
            $avaliacao[$i]->setObs($linha->obs);
            $avaliacao[$i]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
            $i++;
        }

        return $avaliacao; 
    }
}

?>