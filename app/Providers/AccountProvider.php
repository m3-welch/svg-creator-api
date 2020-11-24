<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use ReallySimpleJWT\Token;
use Illuminate\Support\Facades\Validator;

class AccountProvider
{
    public function login(array $data): JsonResponse
    {
        $user = User::where('username', $data['username'])->first();

        $user = $user->authenticatePassword($data['password']);

        if ($user == null) {
            return new JsonResponse([
                "message" => 'Login failed',
                "status" => 401,
            ], 401);
        } else {
            $token = Token::create($data['username'], $user->password, time() + 86400, 'localhost');
            $user->token = $token;
            $user->save();
            return new JsonResponse([
                "message" => 'Login success!',
                "token" => $token,
                "status" => 200,
                "user" => $user
            ], 200);
        }
    }

    public function signup(array $data) {
        $messages = [
            'required' => 'The :attribute field is required',
            'unique' => 'That :attribute already exists',
            'string' => 'That :attribute is required'
        ];

        $validator = Validator::make($data, [
            'username' => ['required', 'unique:App\Models\User,username', 'string'],
            'password' => ['required', 'string'],
            'email' => ['required', 'string']
        ], [],  $messages);


        if ($validator->fails()) {
            return new JsonResponse([
                "message" => $validator->errors()->all(),
                "status" => 400
            ], 400);
        }

        $user = User::create([
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'email' => $data['email']
        ]);

        $user->save();

        return new JsonResponse([
            "message" => 'New user created successfully',
            'status' => 200
        ], 200);
    }
}
