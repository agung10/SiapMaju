<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class AgamaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama_agama'   => ['required', 'max:30'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_agama.required' => 'Nama Agama Tidak Boleh Kosong !',
            'nama_agama.max' => 'Nama Agama Tidak Boleh Lebih Dari 30 Karakter !',
        ];
        
        return $returns;
    }
}
