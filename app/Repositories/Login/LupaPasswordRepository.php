<?php

namespace App\Repositories\Login;
use App\User;

class LupaPasswordRepository
{
    public function __construct()
    {

    }

    public function checkEmail($request)
    {
        $user = User::select('email')
                     ->where('users.email',$request->email)
                     ->first();

        if(!empty($user)){
            return response()->json(['status' => 'success']);
        }else{
            return response()->json(['status' => 'failed']);
        }
    }

    public function sendEmail($email)
    {
        $response = [];
        $code     = 200;

        $data['url'] = \URL::temporarySignedRoute('reset-password',
                                                 now()->addMinutes(30),
                                                 ['email' => \Crypt::encryptString($email)]
                                                );

        try{
            \Mail::send('Login.LupaPassword.mail_reset_password', $data, function($message) use ($email){
                $message->to($email,'Reset Password')
                        ->from('tdp.sikad@mail.com','TDP')
                        ->subject('Reset Password SiapMaju');
            });

            $response['status'] = true;
        }catch(\Exception $e){
            $code = 400;
            $response['status'] = false;
            $response['code'] = $code;
            $response['message'] = $e->getMessage();
        }

        return \Response::json($response,$code);
        
    }

    public function resetPasswordProcess($request)
    {   
        $email = \Crypt::decryptString($request->email);

        $user = User::select('user_id')
                    ->where('email',$email)
                    ->first();

        $user_id = $user->user_id;
        $input['password'] = bcrypt(strtolower($request->password));
        $transaction = false;

        \DB::beginTransaction();
        try{

            User::findOrFail($user_id)
                 ->update($input);

            \DB::commit();
            $transaction = true;
        }catch(\Exception $e){

            \DB::rollback();
            throw $e;
        }

        if($transaction == true){
            return response()->json(['status' => 'success']);
        }
    }
}