<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class passportAuthController extends Controller
{
    /**
     * handle user registration request
     */
    public function registerUser(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
        ]);
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        $access_token_example = $user->createToken('PassportExample@Section.io')->access_token;
        //return the access token we generated in the above step
        return response()->json(['token'=>$access_token_example],200);
    }

    /**
     * login user to our application
     */
    public function loginUser(Request $request){
        $credentials =[
            'email'     => strtolower($request->email),
            'password'  => $request->password
        ];
    
        if(auth()->attempt($credentials)){
            $warga = auth()->user()->anggotaKeluarga;

            if(!$warga->is_active) {
                return response()->json([
                    'error' => 'Unauthorised Access',
                    'status' => 'failed',
                    'msg' => 'Status akun anda tidak aktif, silahkan hubungi Ketua RT setempat'
                ], 401);
            }

            //generate the token for the user
            $user_login_token = auth()->user()->createToken('PassportExample@Section.io')->accessToken;
            
            //now return this token on success login attempt
            return response()->json([
                'token' => $user_login_token,
                'status' => 'success',
                'msg' => 'Login Berhasil'
            ], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json([
                'error' => 'Unauthorised Access',
                'status' => 'failed',
                'msg' => 'Email atau Password Salah'
            ], 401);
        }
    }

    /**
     * This method returns authenticated user details
     */
    public function authenticatedUserDetails(){
        //returns details
        return response()->json(['authenticated-user' => auth()->user()], 200);
    }
}
