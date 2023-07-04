<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' 	=> 'required',
            'password' 	=> 'required|min:6'
        ]);

        $a = 0;
        $data = array();
        $data['errors'] = [];
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }
            return response()->json($data, 400);
        } else {
            $user = User::where('email', '=', $request->email);

            if ($user->count() > 0) {
                $user_find = $user->first();

                if ($user_find->active === 'Yes') {
                    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                        $user = Auth::user();
                        $success['token'] =  $user->createToken('MyApp')->accessToken;
                        return response()->json($success, 200);
                    }
                    else{
                        return response()->json(['errors'=>['Password Invalid']], 400);
                    }
                } else {
                    return response()->json(['errors'=>['User not Active']], 400);
                }
            } else {
                return response()->json(['errors'=>['User not found']], 400);
            }
        }
    }

    public function detail_user() {
        $data['user'] = Auth::user();

        return response()->json($data, 200);
    }

    public function token_notfound()
    {
        return response()->json(['message' => 'token not found'], 403);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json(['message' => 'Logout success'], 200);
    }
}
