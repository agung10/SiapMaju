<?php

namespace App\Http\Requests\Kegiatan;

use Illuminate\Foundation\Http\FormRequest;

class KatKegiatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'nama_kat_kegiatan'   => ['required','max:20'],
            'kode_kat'   => ['required','max:20'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_kat_kegiatan.required' => 'Kategori Kegiatan Tidak Boleh Kosong !',
            'nama_kat_kegiatan.max' => 'Kategori Kegiatan Maximal 20 Karakter!',
            'kode_kat.required' => 'Kode Kategori Tidak Boleh Kosong !',
            'kode_kat.max' => 'Kode Kategori Maximal 20 Karakter!',
        ];
        
        return $returns;
    }
}
