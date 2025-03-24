<?php

/**
     * @OA\Post(path="/api/v1/message/createMessageText", tags={"Message"}, description="Create Message",
     * @OA\RequestBody(
     *  )
     * ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Bad request"),
     *     security={{"bearerAuth":{}}}
     * )
     */

// if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

$part = str_replace("\controllers\message", "", __DIR__);
require_once($part . "/services/MessageAllService.php");

$messageModel = new MessageModel($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->type) || empty($data->status) || empty($data->html) || empty($data->html_m)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

$type = intval($data->type);
$status = intval($data->status);
$html = $data->html;
$html_m = $data->html_m;

$lastCode = $messageModel->getLastMessageCode();
$newCode = generateNewCode($lastCode);

$result = $messageModel->createMessage($newCode, $type, $status, $html, $html_m);

if ($result) {
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Message created successfully", "code" => $newCode]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to create message"]);
}

function generateNewCode($lastCode)
{
    if (!$lastCode) {
        return "MSG0000-001";
    }
    preg_match('/MSG(\d{4})-(\d{3})/', $lastCode, $matches);
    $prefix = intval($matches[1]);
    $suffix = intval($matches[2]);
    if ($suffix >= 999) {
        $prefix++;
        $suffix = 1;
    } else {
        $suffix++;
    }
    return sprintf("MSG%04d-%03d", $prefix, $suffix);
}
