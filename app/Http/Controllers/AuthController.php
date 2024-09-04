<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\AuthValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Helpers\DBHelpers;
use App\Helpers\Func;

class AuthController extends Controller
{
    //


    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidator::validate_rules($request, 'login');


            if (!$validate->fails() && $validate->validated()) {
                if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                    $user = Auth::user(); 
                    $token =  $user->createToken('SnapNetApi')->plainTextToken; 

                    return ResponseHelper::success_response(
                        'Login Successful',
                        $user,
                        $token
                    );
           
                } 
                else{ 
                    return ResponseHelper::error_response(
                        'Invalid login credentials',
                        null,
                        401
                    );
                } 
               
            } else {
                $errors = json_decode($validate->errors());
                $props = ['email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);
                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }


    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = AuthValidator::validate_rules($request, 'register');
            if (!$validate->fails() && $validate->validated()) {
                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ];

                /// Save  /////
                DBHelpers::create_query(User::class, $data);
        
                return ResponseHelper::success_response(
                    'Register was successful',
                    null
                );

            } else {
                $errors = json_decode($validate->errors());
                $props = ['name', 'email', 'password'];
                $error_res = ErrorValidation::arrange_error($errors, $props);
                return ResponseHelper::error_response(
                    'validation error',
                    $error_res,
                    401
                );
            }
        } else {
            return ResponseHelper::error_response(
                'HTTP Request not allowed',
                '',
                404
            );
        }
    }

}
