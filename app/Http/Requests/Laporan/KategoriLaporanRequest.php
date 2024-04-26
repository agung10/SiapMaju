<?php

namespace App\Http\Requests\Laporan;

use Illuminate\Foundation\Http\FormRequest;

class KategoriLaporanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'nama_kategori'   => ['required','max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_kategori.required' => 'Nama Kategori Tidak Boleh Kosong !',
            'nama_kategori.max' => 'Nama Kategori Maximal 255 Karakter !',
        ];
        
        return $returns;
    }
}
