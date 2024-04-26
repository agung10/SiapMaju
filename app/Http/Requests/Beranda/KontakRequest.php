<?php

namespace App\Http\Requests\Beranda;

use Illuminate\Foundation\Http\FormRequest;

class KontakRequest extends FormRequest
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
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],

            'alamat'   => ['required'],
            'no_telp'   => ['required'],
            'mobile'   => ['required'],
            'nama_lokasi'   => ['required'],
            'email'   => ['required'],
            'web'   => ['required'],
            'rekening'   => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'province_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'city_id.required' => 'Kota/Kabupaten Tidak Boleh Kosong !',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',

            'alamat.required' => 'Alamat Tidak Boleh Kosong !',
            'no_telp.required' => 'No Telepon Tidak Boleh Kosong !',
            'mobile.required' => 'Mobile Tidak Boleh Kosong !',
            'nama_lokasi.required' => 'Nama Lokasi Tidak Boleh Kosong !',
            'email.required' => 'Email Tidak Boleh Kosong !',
            'web.required' => 'Web Tidak Boleh Kosong !',
            'rekening.required' => 'Rekening Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
