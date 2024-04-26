<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class BlokRequest extends FormRequest
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
            'rt_id'          => ['required'],
            'rw_id'          => ['required'],
            'kelurahan_id'   => ['required'],
            'nama_blok'      => ['required', 'max:20'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'province_id.required' => 'Provinsi Harus Dipilih !',
            'city_id.required' => 'Kota/Kabupaten Harus Dipilih !',
            'subdistrict_id.required' => 'Kecamatan Harus Dipilih !',
            'rt_id.required' => 'RT Harus Dipilih !',
            'rw_id.required' => 'RW Harus Dipilih !',
            'kelurahan_id.required' => 'Kelurahan Harus Dipilih !',
            'nama_blok.required' => 'Nama Blok Tidak Boleh Kosong !',
            'nama_blok.max' => 'Nama Blok Tidak Boleh Lebih Dari 20 Karakter !',
        ];

        return $returns;
    }
}
