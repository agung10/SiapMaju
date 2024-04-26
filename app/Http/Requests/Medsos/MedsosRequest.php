<?php

namespace App\Http\Requests\Medsos;

use Illuminate\Foundation\Http\FormRequest;

class MedsosRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'icon'   => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'nama'   => ['required','max:150']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'icon.mimes' => 'Format Icon Harus JPEG,PNG,JPG,MPEG !',
            'icon.max' => 'Ukuran Icon Maximal 5MB !',
            'nama.required' => 'Nama Media Sosial Tidak Boleh Kosong !',
            'nama.max' => 'Nama Media Sosial Maksimal 150 Karakter !'
        ];
        
        return $returns;
    }
}
