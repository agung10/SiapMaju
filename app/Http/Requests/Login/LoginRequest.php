<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {   
        $validations = [
            'password' => ['required'],
            'email'   => ['required','email']
        ];
    
        return $validations;
    }

    public function messages() {
        $returns = [
            'email.required' => 'Email Tidak Boleh Kosong !',
            'email.email' => 'Format Email Salah !',
            'password.required' => 'Password Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}
