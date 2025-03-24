<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class MessageAllModel
{
    private $conn;
    private $MsgSize_table = "TMstMMsgSize";
    private $Message_table = "TMstMMessage";
    private $MsgFrame_table = "TMstMMessageFrame ";
    private $TmstVMSmsg_table = "TMstMItmVMSMessage";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllMessageTraffic()
    {
        try {
            $query = "SELECT * FROM " . $this->Message_table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                return json_encode(["message" => "No records found"], JSON_UNESCAPED_UNICODE);
            }

            foreach ($rows as &$row) {
                $row["XVMsgType"] = isset($row["XVMsgType"]) ? intval($row["XVMsgType"]) : null;
                $row["XVMsgInfoType"] = isset($row["XVMsgInfoType"]) ? intval($row["XVMsgInfoType"]) : null;
                $row["XVMsgStatus"] = isset($row["XVMsgStatus"]) ? intval($row["XVMsgStatus"]) : null;
            }

            $resultCount = count($rows);
   
            return json_encode($rows, JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            return json_encode(["error" => "Failed to fetch data", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getAllMessageSignSize()
    {
        try {
            $query = "SELECT * FROM " . $this->MsgSize_table;
    
            $stmt = $this->conn->prepare($query);                          
            $stmt->execute();
    
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            $dropdownData = array();
    
            foreach ($results as $result) {
                if ($result["XVMssCode"] === "001") {
                    continue;
                }
                $dropdownData[] = array(
                    "XVMssCode" => $result["XVMssCode"],
                    "XIMssWPixel" => (int) $result["XIMssWPixel"],
                    "XIMssHPixel" => (int) $result["XIMssHPixel"],
                    "label" => $result["XIMssWPixel"] . 'x' . $result["XIMssHPixel"] . ' PX'
                );
            }
    
            if (count($dropdownData) > 0) {
                return json_encode($dropdownData, JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(["message" => "No records found"], JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Failed to fetch data", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getAllFormatMessage()
    {
        try {
            $query = "SELECT * FROM " . $this->MsgFrame_table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {

                $results = array_map(function ($row) {
                    $row['XVMssCode'] = isset($row['XVMssCode']) ? intval($row['XVMssCode']) : 0;
                    $row['XVMsfFormat'] = isset($row['XVMsfFormat']) ? intval($row['XVMsfFormat']) : 0;
                    $row['XVMsfType'] = isset($row['XVMsfType']) ? intval($row['XVMsfType']) : 0;
                    return $row;
                }, $results);

                return json_encode($results, JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(["message" => "No records found"], JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            return json_encode(["error" => "Failed to fetch data", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    public function deleteMsgByIdModel($XVMsgCode)
    {
        try {
            $this->conn->beginTransaction();
  
            $stmt = $this->conn->prepare("DELETE FROM $this->Message_table WHERE XVMsgCode = ?");
            $stmt->execute([$XVMsgCode]);
    
            $deletedRows = $stmt->rowCount();

            if ($deletedRows > 0) {
                $this->conn->commit();
                return ["status" => true, "message" => "Message deleted successfully."];
            } else {
                $this->conn->rollBack();
                return ["status" => false, "message" => "No matching ID found. Deletion failed."];
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error: " . $e->getMessage());
            return ["status" => false, "error" => "Error: " . $e->getMessage()];
        }
    }
}