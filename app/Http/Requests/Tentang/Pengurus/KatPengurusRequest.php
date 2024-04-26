<?php

namespace App\Http\Requests\Tentang\Pengurus;

use Illuminate\Foundation\Http\FormRequest;

class KatPengurusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama_kategori'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_kategori.required' => 'Nama Kategori Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
