<?php

/**
     * @OA\Post(path="/api/v1/user/update/{id}", tags={"Example_User"}, description="Update user",
     *  @OA\Parameter(
     *    name="id",
     *    required=true,
     *    in="path",
     *    @OA\Schema(
     *      type="integer"
     *    ),
     *  ),
     *  @OA\RequestBody(
     *   @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(required={"first_name", "last_name", "phone"},
     *          @OA\Property(property="first_name", type="string"),
     *          @OA\Property(property="last_name", type="string"),
     *          @OA\Property(property="phone", type="string")
     *      )
     *  )
     * ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Bad request"),
     *     security={{"bearerAuth":{}}}
     * )
     */

if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

$part = str_replace("\controllers\~user", "", __DIR__);
require_once($part . "/services/user/UserService.php");

$userService = new UserService();

$params = array();
$params["id"] = isset($id) && !empty($id) ? $id : "";
$params["first_name"] = isset($_POST["first_name"]) && !empty($_POST["first_name"]) ? $_POST["first_name"] : "";
$params["last_name"] = isset($_POST["last_name"]) && !empty($_POST["last_name"]) ? $_POST["last_name"] : "";
$params["phone"] = isset($_POST["phone"]) && !empty($_POST["phone"]) ? $_POST["phone"] : "";

$data = json_encode($params);
$data = json_decode($data);

$userService->first_name = $data->first_name;
$userService->last_name = $data->last_name;
$userService->phone = $data->phone;
$userService->id = $data->id;

if ($data->id == "") {
    http_response_code(401);
    echo json_encode(
        array(
            "code" => 401,
            "status" => "error",
            "title" => "Oops...",
            "message" => "You didn't enter your id. Please try again."
        )
    );
} elseif ($data->first_name == "") {
    http_response_code(401);
    echo json_encode(
        array(
            "code" => 401,
            "status" => "error",
            "title" => "Oops...",
            "message" => "You didn't enter your firstname. Please try again."
        )
    );
} elseif ($data->last_name == "") {
    http_response_code(401);
    echo json_encode(
        array(
            "code" => 401,
            "status" => "error",
            "title" => "Oops...",
            "message" => "You didn't enter your lastname. Please try again."
        )
    );
} elseif ($data->phone == "") {
    http_response_code(401);
    echo json_encode(
        array(
            "code" => 401,
            "status" => "error",
            "title" => "Oops...",
            "message" => "You didn't enter your phone. Please try again."
        )
    );
} else {
    if ($userService->updateUser()) {
        http_response_code(200);
        echo json_encode(
            array(
                "code" => 200,
                "status" => "success",
                "title" => "Good job!",
                "message" => "You was update successfully."
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(
            array(
                "code" => 401,
                "status" => "error",
                "title" => "Oops...",
                "message" => "You was not update successfully. Please try again."
            )
        );
    }
}
