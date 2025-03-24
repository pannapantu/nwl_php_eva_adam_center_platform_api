<?php

/**
 * @OA\Get(
 *     path="/api/v1/message/getAllFormatMessage", tags={"Message"}, description="Select message all format massage",
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
$response = $messageAllService->getAllFormat();

if ($response) {
    $data = json_decode($response, true);
    $resultCount = count($data);

    if ($resultCount > 0) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(200);
        echo json_encode([
            "response" => [],
            "count" => 0,
            "code" => 200,
            "status" => "success",
            "title" => "Good job!",
            "message" => "No records found."
        ], JSON_UNESCAPED_UNICODE);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "response" => [],
        "count" => 0,
        "code" => 400,
        "status" => "error",
        "title" => "Oops...",
        "message" => "Failed to retrieve data."
    ], JSON_UNESCAPED_UNICODE);
}