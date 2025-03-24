<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

$part_include = str_replace("\services\auth", "", __DIR__);
require_once($part_include . "/services/EvaAdamService.php");

require_once($part_include . "/vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends EvaAdamService
{
    private $db;
    private $key;
    private $iss;
    private $aud;
    private $result;

    public $id;
    public $first_name;
    public $last_name;
    public $phone;

    public function __construct()
    {
        parent::__construct();
       
        $this->db = $this->connention();
        $this->key = $this->jwtKey();
        $this->iss = $this->getIss();
        $this->aud = $this->getAud();
        $part = str_replace("\services\auth", "", __DIR__);
        require_once($part . "/models/UserModel.php");
    }

    public function login()
    {
        $this->result = null;
        
        try {
            $userModel = new UserModel($this->db);
            $userModel->phone = $this->phone;
            $stmt = $userModel->getByPhone();
            if ($stmt) {
                $countRow = 0;
                $user_id = '';
                $stmt = $userModel->getByPhone();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $user_id = $rows[0]["id"];
                $countRow = count($rows);
                if ($countRow > 0) {
                    $iat = time();
                    $exp = $iat + (24 * 60 * 60);
                    $payload = [
                        'iss' => $this->iss,
                        'aud' => $this->aud,
                        'iat' => $iat,
                        'exp' => $exp,
                        'user_id' => $user_id
                    ];
                    $jwt = JWT::encode($payload, $this->key, 'HS256');
                    $this->result = array(
                        'token' => $jwt,
                        'expires' => $exp
                    );
                } else {
                    $this->result = false;
                }
            } else {
                $this->result = false;
            }
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }

    public function register()
    {
        $this->result = null;

        try {
            $userModel = new UserModel($this->db);
            $userModel->first_name = $this->first_name;
            $userModel->last_name = $this->last_name;
            $userModel->phone = $this->phone;
            $stmt = $userModel->getByPhone();
            if ($stmt) {
                $rows = $stmt->fetchAll();
                $countRow = count($rows); // Get the number of rows
                echo $countRow ;
                if ($countRow == 0) {
                    $this->result = $userModel->insert();
                } else {
                    $this->result = false;
                }
            } else {
                $this->result = false;
            }
        } catch (PDOException $e) {
            $this->result = false;
        }

        return $this->result;
    }
}
