<?php

use Firebase\JWT\TokenJWT;
use Bramus\Router\Router;

require_once "./modelo/Router.php";
require_once "./modelo/Curso.php";
require_once "./modelo/Banco.php";
require_once "./modelo/Professor.php";
require_once "./modelo/Avaliacao.php";
require_once "./modelo/AlunosGrupo.php";
require_once "./modelo/TokenJWT.php";
require_once "./modelo/Trabalho.php";

$router  = new Router();

//! ALUNOS-GRUPO 

//? POST

$router->post('/alunos', function(){
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);

    $alunosGrupo = new AlunosGrupo();
    $alunosGrupo->setTrabalho_idTrabalho($obj->trabalho_idTrabalho);
    $alunosGrupo->setMatriculaAluno($obj->matriculaAluno);
    $alunosGrupo->setNomeAluno($obj->nomeAluno);
    $alunosGrupo->setTurmaAluno($obj->turmaAluno);
    
    if ($alunosGrupo->AlunosGrupoExiste() == false){

        $resposta['status'] = $alunosGrupo->createAlunosGrupo();
        $resposta['msg'] = 'cadastrado com sucesso';
        $resposta['dados'] = $alunosGrupo;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'já existe um aluno(a) igual a este(a)!';
        $resposta['dados'] = $alunosGrupo;
    }

    echo json_encode($resposta);
});

//? GET

$router->get('/alunos', function(){
    $alunosGrupo = new AlunosGrupo();
    $resposta['status'] = true;
    $resposta['dados'] = $alunosGrupo->readAll();

    echo json_encode($resposta);
    exit;
});

$router->get('/alunos/(\d+)', function ($matricula) {
    $alunosGrupo = new AlunosGrupo();
    $alunosGrupo->setMatriculaAluno($matricula);

    $resposta['status'] = true;
    $resposta['dados'] = $alunosGrupo->read();
    echo json_encode($resposta);
});

//? PUT

$router->put('/alunos/(\d+)', function($matricula){
    $alunosGrupo = new AlunosGrupo();
    
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);
    
    $alunosGrupo->setTrabalho_idTrabalho($obj->trabalho_idTrabalho);
    $alunosGrupo->setMatriculaAluno($matricula);
    $alunosGrupo->setNomeAluno($obj->nomeAluno);
    $alunosGrupo->setTurmaAluno($obj->turmaAluno);

    $resposta['status'] = $alunosGrupo->update();
    $resposta['msg'] = "atualizado com sucesso";
    $resposta['dados'] = $alunosGrupo;

    echo json_encode($resposta);
});

//? DELETE

$router->delete('/alunos/(\d+)', function($matricula){
    $alunosGrupo = new AlunosGrupo();
    $alunosGrupo->setMatriculaAluno($matricula);

    if($alunosGrupo->AlunosGrupoExiste() == true){
        $resposta['status'] = $alunosGrupo->delete();
        $resposta['msg'] = "excluído com sucesso";
    } else {
        $resposta['status'] = false;
        $resposta['msg'] = "nenhum aluno encontrado!";
    }

    echo json_encode($resposta);
});

//!AVALIACAO

//? POST

$router->post('/avaliacoes', function(){
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido); 

    $avaliacao = new Avaliacao(); 
    $avaliacao->setIdAvaliacao($obj->idAvaliacao);
    $avaliacao->setProfessor_registro($obj->professor_registro);
    $avaliacao->setNotaGeral($obj->notaGeral);
    $avaliacao->setObs($obj->obs);
    $avaliacao->setTrabalho_idTrabalho($obj->trabalho_idTrabalho);
    
    if ($avaliacao->avaliacaoExiste() == false){
        $resposta['status'] = $avaliacao->createAvaliacao();
        $resposta['msg'] = 'cadastrado com sucesso';
        $resposta['dados'] = $avaliacao;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'já existe uma avaliação igual a esta!';
        $resposta['dados'] = $avaliacao;
    }

    echo json_encode($resposta);
});

//? GET

$router->get('/avaliacoes', function(){
    $avaliacao = new Avaliacao();

    $resposta['status'] = true;
    $resposta['dados'] = $avaliacao->readAll();

    echo json_encode($resposta);
    exit;
});

$router->get('/avaliacoes/(\d+)', function ($idAvaliacao) {
    $avaliacao = new Avaliacao();
    $avaliacao->setIdAvaliacao($idAvaliacao);

    $resposta['status'] = true;
    $resposta['dados'] = $avaliacao->read();
    echo json_encode($resposta);
});

$router->get('/avaliacoes/comentarios', function(){
    $avaliacao = new Avaliacao();

    $resposta['status'] = true;
    $resposta['dados'] = $avaliacao->readAllWithComent();

    echo json_encode($resposta);
    exit;
});

//? PUT

$router->put('/avaliacoes/(\d+)', function($idAvaliacao){
    $avaliacao = new Avaliacao();
    
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);
    
    $avaliacao->setIdAvaliacao($idAvaliacao);
    $avaliacao->setProfessor_registro($obj->professor_registro);
    $avaliacao->setNotaGeral($obj->notaGeral);
    $avaliacao->setObs($obj->obs);
    $avaliacao->setTrabalho_idTrabalho($obj->trabalho_idTrabalho);

    $resposta['status'] = $avaliacao->update();
    $resposta['msg'] = "atualizado com sucesso";
    $resposta['dados'] = $avaliacao;

    echo json_encode($resposta);
});

//? DELETE

$router->delete('/avaliacoes/(\d+)', function($idAvaliacao){
    $avaliacao = new Avaliacao();
    $avaliacao->setIdAvaliacao($idAvaliacao);

    $resposta['status'] = $avaliacao->delete();
    $resposta['msg'] = "excluído com sucesso";

    echo json_encode($resposta);
});

//!CURSO

//? POST 

$router->post('/cursos', function(){
    $headers = apache_request_headers();
    
    $tokenJWT = new TokenJWT();

    if ($tokenJWT->validarToken($headers) == true) { 
        $jsonRecebido = file_get_contents('php://input');
        $obj = json_decode($jsonRecebido);
        $curso = new Curso();
        
        $curso->setIdCurso($obj->idCurso);
        $curso->setNomeCurso($obj->nomeCurso);
        
        if ($curso->cursoExiste() == false) {
            $resposta['status'] = $curso->createCurso();
            $resposta['msg'] = 'cadastrado com sucesso';
            $resposta['dados'] = $curso;

        } else {
            $resposta['status'] = false;
            $resposta['msg'] = 'já existe um curso igual a este!';
            $resposta['dados'] = $curso;
        }
    
        echo json_encode($resposta);
    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//? GET

$router->get('/cursos', function(){
    $headers = apache_request_headers();
    
    $tokenJWT = new TokenJWT();
    
    if ($tokenJWT->validarToken($headers) == true) {
        $curso = new Curso();
    
        $resposta['status'] = true;
        $resposta['dados'] = $curso->readAll();
    
        echo json_encode($resposta);
        exit;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

$router->get('/cursos/(\d+)', function($idCurso){
    $headers = apache_request_headers();

    $tokenJWT = new TokenJWT();
    
    if ($tokenJWT->validarToken($headers) == true) {
        $curso = new Curso();
        $curso->setIdCurso($idCurso);

        $resposta['status'] = true;
        $resposta['dados'] = $curso->read();

        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//? PUT

$router->put('/cursos/(\d+)', function($idCurso){
    $headers = apache_request_headers();
    
    $tokenJWT = new TokenJWT();
    
    if ($tokenJWT->validarToken($headers) == true) {
        $curso = new Curso();
        
        $jsonRecebido = file_get_contents('php://input');
        $obj = json_decode($jsonRecebido);
        
        $curso->setIdCurso($idCurso);
        $curso->setNomeCurso($obj->nomeCurso);
    
        $resposta['status'] = $curso->update();
        $resposta['msg'] = "atualizado com sucesso";
        $resposta['dados'] = $curso;
    
        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//? DELETE

$router->delete('/cursos/(\d+)', function($idCurso){
    $headers = apache_request_headers();
    
    $tokenJWT = new TokenJWT();
    
    if ($tokenJWT->validarToken($headers) == true) {
        $curso = new Curso();
        $curso->setIdCurso($idCurso);
    
        $resposta['status'] = $curso->delete();
        $resposta['msg'] = "excluído com sucesso";
    
        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//!PROFESSOR

//? POST

$router->post('/professores', function(){
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);
    
    $professor = new Professor();
    $professor->setRegistro($obj->registro);
    $professor->setNome($obj->nome);
    $professor->setNascimento($obj->nascimento);
    
    if ($professor->professorExiste() == false) {
        $resposta['status'] = $professor->createProfessor();
        $resposta['msg'] = 'cadastrado com sucesso';
        $resposta['dados'] = $professor;
    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'já existe um professor igual a este!';
        $resposta['dados'] = $professor;
    }
    
    echo json_encode($resposta);
});

$router->post('/login', function () {
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);

    $professor = new Professor();
    $professor->setNome($obj->nome);
    $professor->setRegistro($obj->registro);

    $resposta = array();
    if ($professor->verificarProfessorSenha() == true) {
        $tokenJWT = new TokenJWT();
        $novoToken = $tokenJWT->gerarToken(json_encode($professor));
        $resposta['status'] = 'true';
        $resposta['msg'] = "Login efetuado com sucesso";
        $resposta['token'] =  $novoToken;

    } else {
        $resposta['status'] = 'false';
        $resposta['msg'] = "Login inválido";
    }

    echo json_encode($resposta);
});

//? GET

$router->get('/professores', function(){
    $headers = apache_request_headers();

    $tokenJWT = new TokenJWT();

    if ($tokenJWT->validarToken($headers) == true) {
        $professor = new Professor();
        $resposta['status'] = true;
        $resposta['dados'] =  $professor->readAll();

        echo json_encode($resposta);
        exit;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

$router->get('/professores/(\d+)', function($registro){
    $headers = apache_request_headers();

    $tokenJWT = new TokenJWT();

    if ($tokenJWT->validarToken($headers) == true) {
        $professor = new Professor();
        $professor->setRegistro($registro);
        $resposta['status'] = true;
        $resposta['dados'] =  $professor->read();

        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//? PUT

$router->put('/professores/(\d+)', function($registro){
    $headers = apache_request_headers();

    $tokenJWT = new TokenJWT();

    if ($tokenJWT->validarToken($headers) == true) {
        $professor = new Professor();

        $jsonRecebido = file_get_contents('php://input');
        $obj = json_decode($jsonRecebido);

        $professor->setRegistro($registro);
        $professor->setNome($obj->nome);
        $professor->setNascimento($obj->nascimento);
        
        $resposta['status'] = $professor->update();
        $resposta['msg'] = "atualizado com sucesso";
        $resposta['dados'] = $professor;
  
        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }

});

//? DELETE

$router->delete('/professores/(\d+)', function($registro){
    $headers = apache_request_headers();

    $tokenJWT = new TokenJWT();

    if ($tokenJWT->validarToken($headers) == true) {
        $professor = new Professor();
        $professor->setRegistro($registro);

        $resposta['status'] = $professor->delete();
        $resposta['msg'] = "excluído com sucesso";

        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Token inválido';
        echo json_encode($resposta);
    }
});

//!TRABALHO

//? POST

$router->post('/trabalhos', function(){
    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);
    
    $trabalho = new Trabalho();
    $trabalho->setIdTrabalho($obj->idTrabalho);
    $trabalho->setNomeTrabalho($obj->nomeTrabalho);
    $trabalho->setResumo($obj->resumo);
    $trabalho->setCurso_idCurso($obj->curso_idCurso);

    if ($trabalho->trabalhoExiste() == false){
        $resposta['status'] = $trabalho->createTrabalho();
        $resposta['msg'] = 'cadastrado com sucesso';
        $resposta['dados'] = $trabalho;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'já existe um trabalho igual a este!';
        $resposta['dados'] = $trabalho;
    }

    echo json_encode($resposta);
});

//? GET

$router->get('/trabalhos', function(){
    $trabalho = new Trabalho();

    if ($trabalho->trabalhoExiste() == false){
        $resposta['status'] = true;
        $resposta['dados'] = $trabalho->readAll();

        echo json_encode($resposta);
        exit;

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'não há nenhum trabalho cadastrado!';
    }
});

$router->get('/trabalhos/(\d+)', function($idTrabalho){
    $trabalho = new Trabalho();
    $trabalho->setIdTrabalho($idTrabalho);

    if($trabalho->trabalhoExiste() == true){
        $resposta['status'] = true;
        $resposta['dados'] = $trabalho->read();
        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'nenhum trabalho encontrado';
        echo json_encode($resposta);
    }
    
});

$router->get('/trabalhos/alfabetico', function(){
    $trabalho = new Trabalho();

    $resposta['status'] = true;
    $resposta['dados'] = $trabalho->readAlfabeticoProfessores();
    echo json_encode($resposta);    
});

$router->get('/trabalhos/curso/(\d+)', function($curso_idCurso){
    $trabalho = new Trabalho();

    $resposta['status'] = true;
    $resposta['dados'] = $trabalho->readCursoProfessores($curso_idCurso);
    echo json_encode($resposta);    
});

$router->get('/trabalhos/nota', function(){
    $trabalho = new Trabalho();

    $resposta['status'] = true;
    $resposta['dados'] = $trabalho->readOrdemNota();
    echo json_encode($resposta);    
});

$router->get('/trabalhos/melhor', function(){
    $trabalho = new Trabalho();

    $resposta['status'] = true;
    $resposta['dados'] = $trabalho->readMelhorTrabalho();
    echo json_encode($resposta);    
});

$router->get('/trabalhos/melhor/(\d+)', function($curso_idCurso){
    $trabalho = new Trabalho();

    $resposta['status'] = true;
    $resposta['dados'] = $trabalho->readMelhorTrabalhoPorCurso($curso_idCurso);
    echo json_encode($resposta);    
});

//? PUT 

$router->put('/trabalhos/(\d+)', function($idTrabalho){
    $trabalho = new Trabalho();

    $jsonRecebido = file_get_contents('php://input');
    $obj = json_decode($jsonRecebido);

    $trabalho->setIdTrabalho($idTrabalho);
    $trabalho->setNomeTrabalho($obj->nomeTrabalho);
    $trabalho->setResumo($obj->resumo);

    $resposta['status'] = $trabalho->update();
    $resposta['msg'] = "atualizado com sucesso";
    $resposta['dados'] = $trabalho;

    echo json_encode($resposta);
});

//? DELETE

$router->delete('/trabalhos/(\d+)', function($idTrabalho){
    $trabalho = new Trabalho();
    $trabalho->setIdTrabalho($idTrabalho);
    
    if($trabalho->trabalhoExiste() == true){
        $resposta['status'] = $trabalho->delete();
        $resposta['msg'] = "excluído com sucesso";

        echo json_encode($resposta);

    } else {
        $resposta['status'] = false;
        $resposta['msg'] = 'Nenhum trabalho encontrado';

        echo json_encode($resposta);
    }
});

$router->run();

?>