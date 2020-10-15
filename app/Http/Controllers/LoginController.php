<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller {

    /**
     * Handles the login for the users
     *
     * @param Request $request The request object
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        $user = DB::table('users')->where('username', $username)->first();

        if ($user == null) {
            return new JsonResponse([
                "message" => 'Login failed',
                "status" => 401,
            ], 401);
        }

        if (password_verify($password, $user->password)) {
            return new JsonResponse([
                "message" => 'Login success!',
                "status" => 200,
                "username" => $username
            ], 200);
        } else {
            return new JsonResponse([
                "message" => 'Login failed',
                "status" => 401,
            ], 401);
        }

    }

}
