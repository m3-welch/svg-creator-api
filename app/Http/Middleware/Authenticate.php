<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use ReallySimpleJWT\Token;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Validate;
use ReallySimpleJWT\Encode;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authParts = explode(' ', $request->header('authorization'));

        if ($authParts[0] == "") {
            return new JsonResponse([
                "message" => 'Authorization failed',
                "status" => 401,
            ], 401);
        }

        $user_data = DB::table('users')->where('token', $authParts[1])->first();

        if ($user_data == null) {
            return new JsonResponse([
                "message" => 'Authorization failed',
                "status" => 401,
            ], 401);
        }

        $password = $user_data->password;
        $user = new User((array)$user_data);

        if ($authParts[0] == 'Bearer') {
            $valid = Token::validate($authParts[1], $password);

            if(!$valid) {
                $jwt = new Jwt($authParts[1], $password);
                $parse = new Parse($jwt, new Validate(), new Encode());

                try {
                    $parse->validateExpiration();
                    $inDate = true;
                } catch (Exception $e) {
                    $inDate = false;
                }

                if (!$inDate) {
                    return new JsonResponse([
                        "message" => 'Authorization expired.',
                        "status" => 401,
                    ], 401);
                } else {
                    return new JsonResponse([
                        "message" => 'Authorization failed',
                        "status" => 401,
                    ], 401);
                }
            } else {
                app()->instance(User::class, $user);
                return $next($request);
            }
        }
    }
}
