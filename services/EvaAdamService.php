<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once($part_include . "/vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EvaAdamService
{
    private $db;
    private $key;
    private $iss;
    private $aud;

    public function __construct()
    {
        $part = str_replace("services", "", __DIR__);
        require_once($part ."/vendor/autoload.php");

        // Load the .env file
        $dotenv = Dotenv\Dotenv::createImmutable($part);
        $dotenv->load();

        require_once($part . "/config/Database.php");

        $database = new Database($_ENV["HOST"], $_ENV["DATABASENAME"], $_ENV["USER"], $_ENV["PASSWORD"]);

        $this->db = $database->connection();        
        $this->key = $_ENV["JWTKEY"];
        $this->iss = $_ENV["ISS"];
        $this->aud = $_ENV["AUD"];
    }

    public function connention()
    {
        return $this->db;
    }

    public function jwtKey()
    {
        return $this->key;
    }

    public function getIss()
    {
        return $this->iss;
    }

    public function getAud()
    {
        return $this->aud;
    }
    
    public function validateAuthToken()
    {
        try
        {
            $headers = getallheaders();
            if (!isset($headers["Authorization"])) 
            {
                return false; 
            }
            
            $token = str_replace('Bearer ', '', $headers['Authorization']);            
            $token = JWT::decode($token, new Key($this->key, 'HS256'));

            if($token->iss != $this->iss)
            {
                return false;
            }

            if($token->aud != $this->aud)
            {
                return false;
            }

            $current_time = time();
            if ($token->exp < $current_time)
            {
                return false;
            }
            return true;
        }
        catch (Exception $e) {
            echo "validateAuthToken Error: " . $e->getMessage();
            return false;
        }
    }
}

