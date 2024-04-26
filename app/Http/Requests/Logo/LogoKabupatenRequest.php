<?php

namespace App\Http\Requests\Logo;

use Illuminate\Foundation\Http\FormRequest;

class LogoKabupatenRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'logo' => ['mimes:jpg,jpeg,png,mpeg','max:10000'],
            'city_id'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'logo.mimes' => 'Format Logo Harus JPEG,PNG,JPG,MPEG !',
            'logo.max' => 'Ukuran Logo Maximal 10MB !',
            'city_id.required' => 'Kota/Kabupaten Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}