<?php

namespace App\Http\Requests\Kajian;

use Illuminate\Foundation\Http\FormRequest;

class KategoriKajianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'kategori'   => ['required','max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'kategori.required' => 'Kategori Tidak Boleh Kosong !',
            'kategori.max' => 'Kategori Maximal 255 Karakter !',
        ];
        
        return $returns;
    }
}
