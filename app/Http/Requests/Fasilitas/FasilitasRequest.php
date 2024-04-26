<?php

namespace App\Http\Requests\Fasilitas;

use Illuminate\Foundation\Http\FormRequest;

class FasilitasRequest extends FormRequest
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

            'nama_fasilitas' => ['required'],
            'lokasi'         => ['required'],
            'pict1' => $isEdit ? ['mimes:jpeg,png,jpg', 'max:10000']
                : ['required', 'mimes:jpeg,png,jpg', 'max:10000'],
            'pict2' => $isEdit ? ['mimes:jpeg,png,jpg', 'max:10000']
                : ['required', 'mimes:jpeg,png,jpg', 'max:10000'],
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
            
            'nama_fasilitas.required' => 'Nama Fasilitas Tidak Boleh Kosong !',
            'lokasi.required' => 'Lokasi Tidak Boleh Kosong !',
            'pict1.required' => 'Gambar Tidak Boleh Kosong !',
            'pict1.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'pict1.max' => 'Ukuran Gambar Maximal 10MB !',
            'pict2.required' => 'Gambar Tidak Boleh Kosong !',
            'pict2.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'pict2.max' => 'Ukuran Gambar Maximal 10MB !',
        ];

        return $returns;
    }
}
