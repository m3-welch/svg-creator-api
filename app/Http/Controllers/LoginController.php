<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ReallySimpleJWT\Token;

class LoginController extends Controller {

    /**
     * Handles the login for the users
     *
     * @param Request $request The request object
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse {
        $username = base64_decode($request->header('php-auth-user'));
        $password = base64_decode($request->header('php-auth-pw'));

        $user = User::where('username', $username)->first();

        $user = $user->authenticatePassword($password);

        // $user = DB::table('users')->where('username', $username)->first();

        if ($user == null) {
            return new JsonResponse([
                "message" => 'Login failed',
                "status" => 401,
            ], 401);
        } else {
            $token = Token::create($username, $user->password, time() + 3600, 'localhost');
            DB::table('users')
                ->where('username', $username)
                ->update(['token' => $token]);
            return new JsonResponse([
                "message" => 'Login success!',
                "token" => $token,
                "status" => 200,
                "user" => $user
            ], 200);
        }
    }

}
