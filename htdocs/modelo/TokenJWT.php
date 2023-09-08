<?php

namespace Firebase\JWT;

require_once "jwt/JWT.php";
require_once "jwt/Key.php";
require_once "jwt/SignatureInvalidException.php";


use Firebase\JWT\Key;
use Firebase\JWT\JWT;
use DomainException;
use Exception;
use InvalidArgumentException;
use UnexpectedValueException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;

require_once "jwt/ExpiredException.php";

class TokenJWT{
    //chave de criptografia, defina uma chave forte e a mantenha segura.
    private $key = "x9S4q0v+V0IjvHkG20uAxaHx1ijj+q1HWjHKv+ohxp/oK+77qyXkVj/l4QYHHTF3";
    private $alg = 'HS256';     //algoritmo de criptografia
    private $iss = 'http://localhost'; //emissor do token
    private $aud = 'http://localhost'; //destinatário do token
    private $payload = array();
    //tempo de validade do token
    private $duracaoToken = 1800; //1800 segundos = 30 min

    public function gerarToken($jsonDados)
    {
        $headers = [
            'alg' => $this->alg,
            'typ' => 'JWT'
        ];
        $payload = [
            'iss' => $this->iss,  // emissor do token
            'aud' => $this->aud,  // destinatário do token
            'iat' => time(),            // data de criação do tolen
            'exp' => time() + $this->duracaoToken,   //data de expiracao 1800 segundos do atual, 30 minutos
            'dados' => ($jsonDados),
        ];
        //utiliza a biblioteca do firebase para gerar o token com os parametros
        $token = JWT::encode($payload, $this->key, $this->alg, null, $headers);
        return $token;
    }

    public function validarToken($headers){
        $token =  TokenJWT::getAuthorizationToken($headers);
        if (isset($token)) {
            if ($token == "") {
                return false;
            } else {
                try {
                    $decoded = JWT::decode($token,  new key($this->key, $this->alg));
                    $this->setPayload(($decoded));
                    return true;
                } 
                catch (SignatureInvalidException $e) { return false;} 
                catch (DomainException $e) { return false;} 
                catch (InvalidArgumentException $e) { return false;} 
                catch (ExpiredException $e) { return false; } 
                catch (UnexpectedValueException $e) { return false;} 
                catch (Exception $e) { return false; }
            }
        }
        return false;
    }

    public static function getAuthorizationToken($headers)
    {
        if (isset($headers['Authorization'])) {
            $remover  = ["Bearer ", "<", ">", " "];
            $headers = str_replace($remover, "", $headers);
            $token =  $headers['Authorization'];
            return $token;
        } else {
            return "";
        }
    }

    /**
     * Get the value of payload
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set the value of payload
     *
     * @return  self
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get the value of alg
     */
    public function getAlg()
    {
        return $this->alg;
    }

    /**
     * Set the value of alg
     *
     * @return  self
     */
    public function setAlg($alg)
    {
        $this->alg = $alg;

        return $this;
    }
}
