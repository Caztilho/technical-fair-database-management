<?php

require_once "Banco.php";

class AlunosGrupo implements JsonSerializable
{

    private $trabalho_idTrabalho;
    private $matriculaAluno;
    private $nomeAluno;
    private $turmaAluno;


    public function setTrabalho_idTrabalho($trabalho_idTrabalho)
    {
        $this->trabalho_idTrabalho = $trabalho_idTrabalho;
        return $this;
    }

    public function getTrabalho_idTrabalho()
    {
        return $this->trabalho_idTrabalho;
    }

    public function setMatriculaAluno($matriculaAluno)
    {
        $this->matriculaAluno = $matriculaAluno;
        return $this;
    }

    public function getMatriculaAluno()
    {
        return $this->matriculaAluno;
    }

    public function setNomeAluno($nomeAluno)
    {
        $this->nomeAluno = $nomeAluno;
        return $this;
    }

    public function getNomeAluno()
    {
        return $this->nomeAluno;
    }

    public function setTurmaAluno($turmaAluno)
    {
        $this->turmaAluno = $turmaAluno;
        return $this;
    }

    public function getTurmaAluno()
    {
        return $this->turmaAluno;
    }

    public function jsonSerialize()
    {
        $json = array();
        $json['Trabalho_idTrabalho'] = $this->getTrabalho_idTrabalho();
        $json['matriculaAluno'] = $this->getMatriculaAluno();
        $json['nomeAluno'] = $this->getNomeAluno();
        $json['turmaAluno'] = $this->getTurmaAluno();

        return $json;
    }

    public function createAlunosGrupo()
    {
        $this->banco = new Banco();

        $stmt = $this->banco->getConexao()->prepare("insert into alunosgrupo(Trabalho_idTrabalho, matriculaAluno, nomeAluno, turmaAluno) values (?,?, ?, ?)");
        $stmt->bind_param("iiss", $this->trabalho_idTrabalho, $this->matriculaAluno, $this->nomeAluno, $this->turmaAluno);
        $resposta = $stmt->execute();

        return $resposta;
    }

    public function AlunosGrupoExiste()
    {
        $this->banco = new Banco();
        $sql = "select count(*) as qtd from alunosgrupo where matriculaAluno = ?";
        $stmt = $this->banco->getConexao()->prepare($sql);

        $stmt->bind_param("i", $this->matriculaAluno);
        $stmt->execute();
        $resultado = $stmt->get_result();

        while ($linha = $resultado->fetch_object()) {
            if ($linha->qtd == 1) {
                return true;
            }
        }

        return false;
    }

    public function read()
    {
        $this->banco = new Banco();

        $stmt = $this->banco->getConexao()->prepare("select * from alunosgrupo where matriculaAluno=?");

        $stmt->bind_param("i", $this->matriculaAluno);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $alunosGrupo = array();
        while ($linha = $resultado->fetch_object()) {
            $alunosGrupo[0] = new AlunosGrupo();
            $alunosGrupo[0]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
            $alunosGrupo[0]->setMatriculaAluno($linha->matriculaAluno);
            $alunosGrupo[0]->setNomeAluno($linha->nomeAluno);
            $alunosGrupo[0]->setTurmaAluno($linha->turmaAluno);
        }

        return $alunosGrupo;
    }


    public function readAll()
    {
        $this->banco = new Banco();

        $stmt = $this->banco->getConexao()->prepare("select * from alunosgrupo");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $i = 0;

        $alunosGrupo = array();
        while ($linha = $resultado->fetch_object()) {
            $alunosGrupo[$i] = new AlunosGrupo();
            $alunosGrupo[$i]->setTrabalho_idTrabalho($linha->Trabalho_idTrabalho);
            $alunosGrupo[$i]->setMatriculaAluno($linha->matriculaAluno);
            $alunosGrupo[$i]->setNomeAluno($linha->nomeAluno);
            $alunosGrupo[$i]->setTurmaAluno($linha->turmaAluno);

            $i++;
        }

        return $alunosGrupo;
    }

    public function update()
    {
        $this->banco = new Banco();

        $stmt = $this->banco->getConexao()->prepare("update alunosgrupo set Trabalho_idTrabalho = ?, nomeAluno = ?, turmaAluno = ? where matriculaAluno = ?");

        $stmt->bind_param("issi", $this->trabalho_idTrabalho, $this->nomeAluno, $this->turmaAluno, $this->matriculaAluno);

        return $stmt->execute();
    }

    public function delete()
    {
        $this->banco = new Banco();

        $stmt = $this->banco->getConexao()->prepare("delete from alunosgrupo where matriculaAluno = ?");

        $stmt->bind_param("i", $this->matriculaAluno);

        return $stmt->execute();
    }
}

?>