<?php

require_once 'Banco.php';

class Professor implements JsonSerializable {
    
    private $registro;
    private $nome;
    private $nascimento;

    public function getRegistro()
    {
        return $this->registro;
    }

    public function setRegistro($registro)
    {
        $this->registro = $registro;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }   

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNascimento()
    {
        return $this->nascimento;
    }

    public function setNascimento($nascimento)
    {
        $this->nascimento = $nascimento;
        return $this;
    }

    public function jsonSerialize()
    {
        $json = array();
        $json['registro'] = $this->getRegistro();
        $json['nome'] = $this->getNome();
        $json['nascimento'] = $this->getNascimento();
        return $json;
    }

    public function createProfessor()
    {
        $this->banco = new Banco();
        
        $stmt = $this->banco->getConexao()->prepare("insert into professor (registro, nome, nascimento) values (?,?,?)");
        $stmt->bind_param("iss", $this->registro, $this->nome, $this->nascimento);
        $resposta = $stmt->execute();
        
        return $resposta;
    }

    public function professorExiste()
    {
        $this->banco = new Banco();  
        $sql = "select count(*) as qtd from professor where registro = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        
        $stmt->bind_param("i", $this->registro);
        $stmt->execute();  
        $resultado = $stmt->get_result();
        
        while ($linha = $resultado->fetch_object()){     
            if ($linha->qtd == 1) {
                return true;
            }
        }

        return false;
    }

    public function delete()
    {
        $this->banco = new Banco();  
        $stmt = $this->banco->getConexao()->prepare("delete from professor where registro = ?");
        
        $stmt->bind_param("i", $this->registro);
        
        return $stmt->execute(); 
    }

    public function update()
    {   
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("update professor set nome=?, nascimento=? where registro = ?");
        
        $stmt->bind_param("ssi", $this->nome, $this->nascimento, $this->registro);

        return $stmt->execute();  
    }

    public function read()
    {
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from professor where registro=?");
        
        $stmt->bind_param("i", $this->registro);
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 
        
        $professor= array();
        while ($linha = $resultado->fetch_object()){
            $professor[0] = new Professor();
            $professor[0]->setRegistro($linha->registro);
            $professor[0]->setNome($linha->nome);
            $professor[0]->setNascimento($linha->nascimento);
        }

        return $professor; 
    }
    
    
    public function readAll()
    { 
        $this->banco = new Banco(); 
        
        $stmt = $this->banco->getConexao()->prepare("select * from professor ");
        $stmt->execute();  
        $resultado = $stmt->get_result();  
        $professor = array(); $i = 0;
        
        while ($linha = $resultado->fetch_object()){
            $professor[$i] = new Professor(); 
            
            $professor[$i]->setRegistro($linha->registro);
            $professor[$i]->setNome($linha->nome);
            $professor[$i]->setNascimento($linha->nascimento);
            $i++;
        }
        
        return $professor; 
    }

    public function verificarProfessorSenha(){
        $this->banco = new Banco(); 
        $sql = "select count(*) as qtd from professor where registro = ? and nome = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);
        $stmt->bind_param("is", $this->registro, $this->nome);
        $stmt->execute();  
        $resultado = $stmt->get_result();   
        
        while ($linha = $resultado->fetch_object()) { 
            
            if ($linha->qtd == 1) {
                return true;
            }
        }

        return false;
    }
}

?>