<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class RTRequest extends FormRequest
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
            'rt'             => ['required', 'max:4'],
            'rw_id'          => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'province_id.required' => 'Provinsi Harus Dipilih !',
            'city_id.required' => 'Kota/Kabupaten Harus Dipilih !',
            'subdistrict_id.required' => 'Kecamatan Harus Dipilih !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rt.required' => 'RT Tidak Boleh Kosong !',
            'rt.max' => 'RT maksimal 4 karakter',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
