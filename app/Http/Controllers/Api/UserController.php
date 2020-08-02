<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;

class UserController extends Controller
{
    /*
    ** user register
    */
    public function register(Request $request)
    {
        $validator = Validator::make(request()->input(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        request()->merge(['password' => bcrypt(request('password'))]);

        $user = User::create(request()->input());

        $success['token'] = $user->createToken('createToken')->accessToken;

        return response()->json($success);
    }

    public function login(Request $request)
    {

        /*
        ** use the validator class to better handle validation errors
        */
        $validateData = Validator::make(request()->input(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validateData->errors()) {
            return response()->json(['error' => $validateData->errors()], 401);
        }

        /*
        ** after validation, if the email is not found, it warns non-user
        */
        if (!User::select('id')
            ->where('email', $request->input('email'))
            ->first()) {
            return response(['type' => 'usuario', 'message' => 'Usuario no encontrado'], 404);
        }

        /*
        ** if the password is not found, the user is notified
        */
        if (!auth()->attempt($request->input())) {
            return response(['type' => 'password', 'message' => 'Contraseña incorrecta'], 401);
        }
        
        /*
        ** if everything went well, the token is created and it is returned to the client
        */
        $accessToken = auth()->user()->createToken('loginToken')->accessToken;
        return response(['user' => auth()->user(), 'accessToken' => $accessToken]);
    }
}
