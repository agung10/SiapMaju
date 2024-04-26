<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class RWRequest extends FormRequest
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
            'kelurahan_id'   => ['required'],
            'rw'   => ['required', 'max:5'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'province_id.required' => 'Provinsi Harus Dipilih !',
            'city_id.required' => 'Kota/Kabupaten Harus Dipilih !',
            'subdistrict_id.required' => 'Kecamatan Harus Dipilih !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rw.required' => 'RW Tidak Boleh Kosong !',
            'rw.max' => 'RW maksimal 5 karakter',
        ];
        
        return $returns;
    }
}
