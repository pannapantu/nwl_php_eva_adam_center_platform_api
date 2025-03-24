<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

$part_include = str_replace("\services", "", __DIR__);
require_once($part_include . "/services/EvaAdamService.php");

class MessageAllService extends EvaAdamService
{
    private $db;
    private $key;
    private $result;

    public $XVMsgCode;

    public function __construct()
    {
        parent::__construct();

        $this->db = $this->connention();

        $part = str_replace("\services", "", __DIR__);
        require_once($part . "/models/MessageAllModel.php");
    }

    public function getAllTraffic()
    {
        $headers = apache_request_headers();

        $this->result = null;

        $messageAllTraffic = new MessageAllModel($this->db);
        $this->result = $messageAllTraffic->getAllMessageTraffic();

        return $this->result;
    }
    
    public function getAllSignSize()
    {
        $headers = apache_request_headers();

        $this->result = null;

        $messageAllSignSizel = new MessageAllModel($this->db);
        $this->result = $messageAllSignSizel->getAllMessageSignSize();

        return $this->result;
    }

    public function getAllFormat()
    {
        $headers = apache_request_headers();

        $this->result = null;

        $messageAllFormat = new MessageAllModel($this->db);
        $this->result = $messageAllFormat->getAllFormatMessage();

        return $this->result;
    }

    public function deleteMessageById()
    {
        $headers = apache_request_headers();
        if (empty($this->XVMsgCode) || !is_string($this->XVMsgCode) || strlen($this->XVMsgCode) === 0) {
            return [
                'status' => 'error',
                'message' => 'Invalid or missing XVMsgCode.'
            ];
        }
        $deleteById = new MessageAllModel($this->db);
        $this->result = $deleteById->deleteMsgByIdModel($this->XVMsgCode);

        return $this->result;
    }
}