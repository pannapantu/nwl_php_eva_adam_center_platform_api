<?php

/**
 * @OA\Info(title="Eva Adam Center API", version="1.0")
 *  @OA\SecurityScheme(
 *      type="http",
 *      description="Enter token",
 *      name="Authorization",
 *      in="header",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      securityScheme="bearerAuth",
 * )
 */

 /**
     * @OA\Post(path="/api/v1/login", tags={"Example_Authorization"}, description="Get token",
     * @OA\RequestBody(
     *  @OA\MediaType(
     *      mediaType="multipart/form-data",
     *      @OA\Schema(required={"phone"},
     *          @OA\Property(property="phone", type="string")
     *      )
     *  )
     * ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Bad request")
     * )
     */

if (!defined('BASEPATH')) exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');

$part = str_replace("\controllers\~auth", "", __DIR__);
require_once($part . "/services/auth/AuthService.php");

$authService = new AuthService();

$params = array();
$params["phone"] = isset($_POST["phone"]) && !empty($_POST["phone"]) ? $_POST["phone"] : "";

$data = json_encode($params);
$data = json_decode($data);

$authService->phone = $data->phone;

if ($data->phone == "") {
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
    if (!empty($authService->login()) && $authService->login()) {
        http_response_code(200);
        echo json_encode(
            array(
                "response" => $authService->login(),
                "code" => 200,
                "status" => "success",
                "title" => "Good job!",
                "message" => "You was authorization successfully."
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(
            array(
                "code" => 401,
                "status" => "error",
                "title" => "Oops...",
                "message" => "You was not authorization successfully. Please try again."
            )
        );
    }
}