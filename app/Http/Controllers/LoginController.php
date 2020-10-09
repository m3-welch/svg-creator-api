<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller {

    /**
     * Handles the login for the users
     *
     * @param Request $request The request object
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse {
        $requestData = $request->json()->all();
        $username = $requestData['username'];
        $password = $requestData['password'];

        if (isset($username) && isset($password)) {
            return new JsonResponse([
                "message" => 'Login success!',
                "status" => 200
            ], 200);
        }
    }

}
