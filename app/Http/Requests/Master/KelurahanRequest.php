<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class KelurahanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'nama'           => ['required', 'max:30'],
            'alamat'         => ['required'],
            'kode_pos'       => ['required', 'max:7'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'province_id.required' => 'Provinsi Harus Dipilih !',
            'city_id.required' => 'Kota/Kabupaten Harus Dipilih !',
            'subdistrict_id.required' => 'Kecamatan Harus Dipilih !',
            'nama.required' => 'Nama Kelurahan Tidak Boleh Kosong !',
            'nama.max' => 'Nama Kelurahan maksimal 30 karakter',
            'alamat.required' => 'Alamat Kelurahan Tidak Boleh Kosong !',
            'kode_pos.max' => 'Kode Pos maksimal 7 karakter',
        ];
        
        return $returns;
    }
}
