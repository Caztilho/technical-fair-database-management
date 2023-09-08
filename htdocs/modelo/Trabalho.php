<?php

require_once "Banco.php";
require_once "Avaliacao.php";

class Trabalho implements JsonSerializable {
    
    private $idTrabalho;
    private $nomeTrabalho;
    private $resumo;
    private $curso_idCurso;

    public function setIdTrabalho($idTrabalho){
        $this->idTrabalho = $idTrabalho;
    }

    public function getIdTrabalho(){
        return $this->idTrabalho;
    }

    public function setNomeTrabalho($nomeTrabalho){
        $this->nomeTrabalho = $nomeTrabalho;
    }

    public function getNomeTrabalho(){
        return $this->nomeTrabalho;
    }

    public function setResumo($resumo){
        $this->resumo = $resumo;
    }

    public function getResumo(){
        return $this->resumo;
    }

    public function setCurso_idCurso($curso_idCurso){
        $this->curso_idCurso = $curso_idCurso;
    }
    public function getCurso_idCurso(){
        return $this->curso_idCurso;
    }

    public function jsonSerialize()
    {
        $json = array();
        $json['idTrabalho'] = $this->getIdTrabalho();
        $json['nomeTrabalho'] = $this->getNomeTrabalho();
        $json['resumo'] = $this->getResumo();
        $json['curso_idCurso'] = $this->getCurso_idCurso();
        return $json;
    }

    public function createTrabalho()
    {
        $this->banco = new Banco();
       
        $stmt = $this->banco->getConexao()->prepare("insert into trabalho(idTrabalho, nomeTrabalho, resumo, Curso_idCurso) values (?,?,?,?)");
        $stmt->bind_param("issi", $this->idTrabalho, $this->nomeTrabalho, $this->resumo, $this->curso_idCurso);
        $resposta = $stmt->execute();
        
        return $resposta;
    }

    public function trabalhoExiste(){
        $this->banco = new Banco(); 
        $sql = "select count(*) as qtd from trabalho where idTrabalho = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        
        $stmt->bind_param("i", $this->idTrabalho);
        $stmt->execute();  
        $resultado = $stmt->get_result();
        
        while($linha = $resultado->fetch_object()){
            if ($linha->qtd == 1){
                return true;
            }
        }

        return false;
    }

    public function read(){
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from trabalho where idTrabalho=?");
        
        $stmt->bind_param("i", $this->idTrabalho);
        
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        
        $trabalho = array();
        while ($linha = $resultado->fetch_object()) {
            $trabalho[0] = new Trabalho();
            $trabalho[0]->setIdTrabalho($linha->idTrabalho);
            $trabalho[0]->setNomeTrabalho($linha->nomeTrabalho);
            $trabalho[0]->setResumo($linha->resumo);
            $trabalho[0]->setCurso_idCurso($linha->Curso_idCurso);
        }

        return $trabalho; 
    }
    
    
    public function readAll(){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from trabalho");
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        $trabalho = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()){
            $trabalho[$i] = new Trabalho(); 
            
            $trabalho[$i]->setIdTrabalho($linha->idTrabalho);
            $trabalho[$i]->setNomeTrabalho($linha->nomeTrabalho);
            $trabalho[$i]->setResumo($linha->resumo);
            $trabalho[$i]->setCurso_idCurso($linha->Curso_idCurso);
            $i++;
        }

        if(isset($trabalho[0])){
            return $trabalho; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }

    
    public function readAlfabeticoProfessores(){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from trabalho order by nomeTrabalho asc");
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        $trabalho = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()){
            $trabalho[$i] = new Trabalho(); 
            
            $trabalho[$i]->setIdTrabalho($linha->idTrabalho);
            $trabalho[$i]->setNomeTrabalho($linha->nomeTrabalho);
            $trabalho[$i]->setResumo($linha->resumo);
            $trabalho[$i]->setCurso_idCurso($linha->Curso_idCurso);
            $i++;
        }

        if(isset($trabalho[0])){
            return $trabalho; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }

    
    public function readCursoProfessores($curso_idCurso){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from trabalho where Curso_idCurso = ?");
        $stmt->bind_param("i", $curso_idCurso);
        $stmt->execute(); 

        $resultado = $stmt->get_result(); 
        $trabalho = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()){
            $trabalho[$i] = new Trabalho(); 
            
            $trabalho[$i]->setIdTrabalho($linha->idTrabalho);
            $trabalho[$i]->setNomeTrabalho($linha->nomeTrabalho);
            $trabalho[$i]->setResumo($linha->resumo);
            $trabalho[$i]->setCurso_idCurso($linha->Curso_idCurso);
            $i++;
        }

        if(isset($trabalho[0])){
            return $trabalho; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }
    
    
    public function readOrdemNota(){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from avaliacao order by notaGeral desc");
        $stmt->execute(); 

        $ordemNotas = $stmt->get_result(); 
        $i = 0;
        
        $trabalho = array();
        while ($linha = $ordemNotas->fetch_object()){
            $stmt = $this->banco->getConexao()->prepare("select * from trabalho where idTrabalho = ?");
            $idTrabalho = $linha->Trabalho_idTrabalho;
            $stmt->bind_param("i", $idTrabalho);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $row = $resultado->fetch_object();
            $trabalho[$i] = new Trabalho();
            $trabalho[$i]->setIdTrabalho($row->idTrabalho);
            $trabalho[$i]->setNomeTrabalho($row->nomeTrabalho);
            $trabalho[$i]->setResumo($row->resumo);
            $trabalho[$i]->setCurso_idCurso($row->Curso_idCurso);

            $i++;
        } 

        if(isset($trabalho[0])){
            return $trabalho; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }


    public function readMelhorTrabalho(){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from avaliacao order by notaGeral desc limit 1");
        $stmt->execute(); 
        $ordemNotas = $stmt->get_result(); 
        
        $i = 0;
        
        while ($linha = $ordemNotas->fetch_object()){
            $stmt = $this->banco->getConexao()->prepare("select * from trabalho where idTrabalho = ?");
            $idTrabalho = $linha->Trabalho_idTrabalho;
            $stmt->bind_param("i", $idTrabalho);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $row = $resultado->fetch_object();
            $trabalho[0] = new Trabalho();
            $trabalho[0]->setIdTrabalho($row->idTrabalho);
            $trabalho[0]->setNomeTrabalho($row->nomeTrabalho);
            $trabalho[0]->setResumo($row->resumo);
            $trabalho[0]->setCurso_idCurso($row->Curso_idCurso);

            $i++;
        }

        if(isset($trabalho[0])){
            return $trabalho; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }

    public function readMelhorTrabalhoPorCurso($curso_idCurso){ 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from avaliacao order by notaGeral desc limit 1");
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        
        $i = 0;
        
        while ($linha = $resultado->fetch_object()){
            $avaliacao[$i] = new Avaliacao(); 
            
            $avaliacao[$i]->setIdAvaliacao($linha->idAvaliacao);
            $avaliacao[$i]->setProfessor_registro($linha->professor_registro);
            $avaliacao[$i]->setNotaGeral($linha->notaGeral);
            $avaliacao[$i]->setObs($linha->obs);
            $avaliacao[$i]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
            $i++;
        }

        if(isset($avaliacao[0])){
            return $avaliacao; 
        } else {
            return "Nenhum trabalho cadastrado";
        }
    }

    public function update(){   
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("update trabalho set nomeTrabalho = ?, resumo = ? where idTrabalho = ?");
        $stmt->bind_param("ssi", $this->nomeTrabalho, $this->resumo, $this->idTrabalho);

        return $stmt->execute(); 
    }

    public function delete()
    {
        $this->banco = new Banco();  
        
        $stmt = $this->banco->getConexao()->prepare("delete from trabalho where idTrabalho = ?"); 
        $stmt->bind_param("i", $this->idTrabalho);
        
        return $stmt->execute(); 
    }
}

?>