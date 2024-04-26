<?php

namespace App\Http\Requests\Pengumuman;

use Illuminate\Foundation\Http\FormRequest;

class PengumumanRequest extends FormRequest
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

            'image1'   => ['mimes:jpeg,png,jpg', 'max:10000'],
            'image2'   => ['mimes:jpeg,png,jpg', 'max:10000'],
            'image3'   => ['mimes:jpeg,png,jpg', 'max:10000'],
            'pengumuman'   => ['required'],
            'tanggal'   => ['required'],
            'aktif'   => ['required'],
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

            'image1.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image1.max' => 'Ukuran Gambar Maximal 10MB !',
            'image2.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image2.max' => 'Ukuran Gambar Maximal 10MB !',
            'image3.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image3.max' => 'Ukuran Gambar Maximal 10MB !',
            'pengumuman.required' => 'Pengumuman Tidak Boleh Kosong !',
            'pengumuman.max' => 'Pengumuman Maximal 255 Karakter !',
            'tanggal.required' => 'Tanggal Tidak Boleh Kosong !',
            'aktif.required' => 'Aktif Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
