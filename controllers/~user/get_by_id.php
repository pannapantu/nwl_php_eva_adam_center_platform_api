<?php

 /**
     * @OA\Get(
     *     path="/api/v1/user/{id}", tags={"Example_User"}, description="Select user by id",
     *     @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Bad request"),
     *     security={{"bearerAuth":{}}}
     * )
     */

if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');

$part = str_replace("\controllers\~user", "", __DIR__);
require_once($part . "/services/user/UserService.php");

$userService = new UserService();

$params = array();
$params["id"] = isset($id) && !empty($id) ? $id : "";

$data = json_encode($params);
$data = json_decode($data);
$userService->id = $data->id;

if ($data->id == "") {
    http_response_code(200);
    echo json_encode(
        array(
            "code" => 204,
            "status" => "error",
            "title" => "Oops...",
            "message" => "You didn't enter your id. Please try again."
        )
    );
} else {
    $stmt = $userService->getUserById();
    if ($stmt) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $resultCount = count($rows); // Get the number of rows
        if ($resultCount > 0) {
            http_response_code(200);
            $arr = array();
            $arr["response"] = array();
            $arr["count"] = $resultCount;
            $arr["code"] = 200;
            $arr["status"] = "success";
            $arr["title"] = "Good jod!";
            $arr["message"] = $resultCount . " records";
            array_push($arr["response"], $rows);
            echo json_encode($arr);
        } else {
            http_response_code(200);
            echo json_encode(
                array(
                    "response" => array(),
                    "count" => 0,
                    "code" => 200,
                    "status" => "success",
                    "title" => "Good jod!",
                    "message" => "No records found."
                )
            );
        }
    } else {
        http_response_code(200);
        echo json_encode(
            array(
                "response" => array(),
                "count" => 0,
                "code" => 400,
                "status" => "error",
                "title" => "Oops...",
                "message" => "Please try again."
            )
        );
    }
}
