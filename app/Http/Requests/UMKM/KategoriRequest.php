<?php

namespace App\Http\Requests\UMKM;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama'   => ['required','max:150'],
            'keterangan'   => ['required','max:255']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama.required' => 'Nama Kategori Tidak Boleh Kosong !',
            'nama.max' => 'Nama Kategori Maksimal 150 Karakter !',
            'keterangan.required' => 'Keterangan Kategori Tidak Boleh Kosong !',
            'keterangan.max' => 'Keterangan Kategori Maksimal 255 Karakter !'
        ];
        
        return $returns;
    }
}
