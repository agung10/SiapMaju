<?php

namespace App\Http\Requests\Tentang\Pengurus;

use Illuminate\Foundation\Http\FormRequest;

class PengurusRequest extends FormRequest
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
            'province_id'     => ['required'],
            'city_id'         => ['required'],
            'subdistrict_id'  => ['required'],
            'kelurahan_id'    => ['required'],
            'rw_id'           => ['required'],
            'rt_id'           => ['required'],
            'photo'           => ['mimes:jpeg,png,jpg', 'max:10000'],
            'kat_pengurus_id' => ['required'],
            'nama'            => ['required'],
            'jabatan'         => ['required'],
            'alamat'          => ['required'],
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
            'photo.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'photo.max' => 'Ukuran Gambar Maximal 10MB !',
            'nama.required' => 'Nama Tidak Boleh Kosong !',
            'jabatan.required' => 'Jabatan Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Tidak Boleh Kosong !',
            'kat_pengurus_id.required' => 'Kategori Pengurus Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
