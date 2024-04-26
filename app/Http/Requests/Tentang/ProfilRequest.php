<?php

namespace App\Http\Requests\Tentang;

use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $route = \Request::segment(4);
        $isEdit = $route === 'edit';

        $validations = [
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required'],
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],
            'gambar_profile' => $isEdit ? ['mimes:jpeg,png,jpg', 'max:10000']
                : ['required', 'mimes:jpeg,png,jpg', 'max:10000'],
            'isi_profile'    => ['required'],
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
            'gambar_profile.required' => 'Gambar Tidak Boleh Kosong !',
            'gambar_profile.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'gambar_profile.max' => 'Ukuran Gambar Maximal 10MB !',
            'isi_profile.required' => 'Isi Profil Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
