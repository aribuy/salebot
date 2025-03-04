<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Traits\ApiReturnFormatTrait;
use App\Traits\ImageTrait;
use App\Traits\SendMailTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiReturnFormatTrait,SendMailTrait,ImageTrait;


    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseWithError(__('required_field_missing'), $validator->errors(), 422);
        }
        try {
            $user = User::where('email', $request->email)->where('user_type', 'client-staff')->first();

            $check_user_status = userAvailability($user);

            if (!$check_user_status['status']) {
                return $this->responseWithError($check_user_status['message'], [], $check_user_status['code']);
            }

            $credentials       = $request->only('email', 'password');

            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return $this->responseWithError(__('invalid_credentials'), [], 401);
                }
            } catch (JWTException $e) {
                return $this->responseWithError(__('unable_to_login'), [], 422);

            } catch (\Exception $e) {
                return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
            }

            Auth::attempt($credentials);

            return $this->responseWithSuccess(__('login_successfully'), authData($user, $token));
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }


    public function logout(): JsonResponse
    {
        try {

            JWTAuth::getToken();
            JWTAuth::parseToken()->invalidate(true);

            return $this->responseWithSuccess(__('logout_successfully'));
        } catch (\Exception $e) {
            return $this->responseWithError('An unexpected error occurred. Please try again later.', [], 500);
        }
    }
}
