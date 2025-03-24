<?php

/**
 * @OA\Get(
 *     path="/api/v1/message/getAllTraffic", tags={"Message"}, description="Select message all",
 *     @OA\Response(response="200", description="Success"),
 *     @OA\Response(response="400", description="Bad request"),
 *     security={{"bearerAuth":{}}}
 * )
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

$part = str_replace("\controllers\message", "", __DIR__);
require_once($part . "/services/MessageAllService.php");

$messageAllService = new MessageAllService();
$response = $messageAllService->getAllTraffic();

if ($response) {

    $data = json_decode($response, true);

    $resultCount = count($data);

    if ($resultCount > 0) {
        http_response_code(200); 
        $arr = array();
        $arr["response"] = $data;
        $arr["count"] = $resultCount; 
        $arr["code"] = 200;
        $arr["status"] = "success";
        $arr["title"] = "Good job!";
        $arr["message"] = $resultCount . " records"; 

        echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(200);
        echo json_encode(
            array(
                "response" => array(),
                "count" => 0, 
                "code" => 200,
                "status" => "success",
                "title" => "Good job!",
                "message" => "No records found."
            ), 
            JSON_UNESCAPED_UNICODE
        );
    }
} else {
    http_response_code(400);
    echo json_encode(
        array(
            "response" => array(),
            "count" => 0,
            "code" => 400,
            "status" => "error",
            "title" => "Oops...",
            "message" => "Please try again."
        ),
        JSON_UNESCAPED_UNICODE
    );
}