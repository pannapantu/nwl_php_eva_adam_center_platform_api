<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

$part_include = str_replace("\services\user", "", __DIR__);
require_once($part_include . "/services/EvaAdamService.php");
require_once($part_include . "/vendor/autoload.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserService extends EvaAdamService
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

        $part = str_replace("\services\user", "", __DIR__);
        require_once($part . "/models/UserModel.php");
    }

    public function getUserAll()
    {
        $this->result = null;
            try {
                if($this->validateAuthToken())
                {
                    $userModel = new UserModel($this->db);
                    $this->result = $userModel->getAll();
                } else {
                    $this->result = false;
                }               
            } catch (PDOException $e) {
                $this->result = false;
            }
        return $this->result;
    }

    public function getUserById()
    {
        $this->result = null;
        try {
            if($this->validateAuthToken())
            {
                $userModel = new UserModel($this->db);
                $userModel->id = $this->id;
                $this->result = $userModel->getById();
            } else {
                $this->result = false;
            }               
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }

    public function createUser()
    {
        $this->result = null;
        try {
            if($this->validateAuthToken())
            {
                $userModel = new UserModel($this->db);
                $userModel->first_name = $this->first_name;
                $userModel->last_name = $this->last_name;
                $userModel->phone = $this->phone;
                $stmt = $userModel->getByPhone();
                    if ($stmt) {
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $countRow = count($rows);
                            if ($countRow == 0) {
                                $this->result = $userModel->insert();
                            } else {
                                $this->result = false;
                            }
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
    
    public function updateUser()
    {
        $this->result = null;
        try {
            if($this->validateAuthToken())
            {
                $userModel = new UserModel($this->db);
                $userModel->first_name = $this->first_name;
                $userModel->last_name = $this->last_name;
                $userModel->phone = $this->phone;
                $userModel->id = $this->id;
                $stmt = $userModel->getByPhone();
                if ($stmt) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $countRow = count($rows);
                    if ($countRow == 0) {
                        $this->result = $userModel->update();
                    } else {
                        if ($this->id == $rows[0]['id']) {
                            $this->result = $userModel->update();
                        } else {
                            $this->result = false;
                        }
                    }
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
   
    public function deleteUser()
    {
        $this->result = null;
        try {
            if($this->validateAuthToken())
            {
                $userModel = new UserModel($this->db);
                $userModel->id = $this->id;
                $this->result = $userModel->delete();
            } else {
                $this->result = false;
            }               
        } catch (PDOException $e) {
            $this->result = false;
        }
        return $this->result;
    }
}