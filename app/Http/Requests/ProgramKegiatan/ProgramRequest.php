<?php

namespace App\Http\Requests\ProgramKegiatan;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequest extends FormRequest
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

            'image'   => $isEdit ? ['mimes:jpeg,png,jpg', 'max:10000']
                : ['required', 'mimes:jpeg,png,jpg', 'max:10000'],
            'nama_program'   => ['required'],
            'pic'   => ['required'],
            'tanggal'   => ['required'],
            'program'   => ['required'],
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

            'image.required' => 'Gambar Tidak Boleh Kosong !',
            'image.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image.max' => 'Ukuran Gambar Maximal 10MB !',
            'nama_program.required' => 'Nama Program Tidak Boleh Kosong !',
            'pic.required' => 'PIC Tidak Boleh Kosong !',
            'tanggal.required' => 'Tanggal Tidak Boleh Kosong !',
            'program.required' => 'Program Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
