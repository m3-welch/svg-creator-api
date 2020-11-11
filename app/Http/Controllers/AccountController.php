<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Providers\AccountProvider;

class AccountController extends Controller {

    private $accountProvider;

    public function __construct(AccountProvider $accountProvider)
    {
        $this->accountProvider = $accountProvider;
    }

    /**
     * Handles the login for the users
     *
     * @param Request $request The request object
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse {
        $username = base64_decode($request->header('php-auth-user'));
        $password = base64_decode($request->header('php-auth-pw'));

        return $this->accountProvider->login([
            'username' => $username,
            'password' => $password
        ]);
    }

    public function signup(Request $request): JsonResponse {
        $requestData = $request->getContent();
        $requestData = explode('&', $requestData);

        $requestItems = [];

        foreach($requestData as $item) {
            $data = explode('=', $item);
            $requestItems[$data[0]] = urldecode($data[1]);
        }

        return $this->accountProvider->signup($requestItems);
    }
}
