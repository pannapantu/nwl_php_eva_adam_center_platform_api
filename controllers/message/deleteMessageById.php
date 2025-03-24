<?php

/**
 * @OA\Post(
 *     path="/api/v1/message/deleteMessageById/{XVMsgCode}", 
 *     tags={"Message"}, 
 *     description="Delete a message by ID",
 *     @OA\Parameter(
 *         name="XVMsgCode",
 *         in="path",
 *         description="ID of the message to delete",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(response="200", description="Message deleted successfully"),
 *     @OA\Response(response="400", description="Bad request"),
 *     security={{"bearerAuth":{}}}
 * )
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

$part = str_replace("\controllers\message", "", __DIR__);
require_once($part . "/services/MessageAllService.php");

$params = array();
$params["XVMsgCode"] = isset($XVMsgCode) && !empty($XVMsgCode) ? $XVMsgCode : "";

$data = json_encode($params);
$data = json_decode($data);

$messageAllService = new MessageAllService();
$messageAllService->XVMsgCode = $data->XVMsgCode;
$response = $messageAllService->deleteMessageById();

if ($response["status"]) {
    http_response_code(200);
    echo json_encode([
        "response" => [],
        "count" => 1,
        "code" => 200,
        "status" => "success",
        "title" => "Success",
        "message" => "Message deleted successfully."
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(400);
    error_log('Delete failed: ' . ($response['message'] ?? 'Unknown error'));
    echo json_encode([
        "response" => [],
        "count" => 0,
        "code" => 400,
        "status" => "error",
        "title" => "Failed to delete...",
        "message" => $response['message'] ?? "Failed to delete message."
    ], JSON_UNESCAPED_UNICODE);
}