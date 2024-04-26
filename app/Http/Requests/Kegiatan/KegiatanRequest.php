<?php

namespace App\Http\Requests\Kegiatan;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'kat_kegiatan_id'   => ['required'],
            'nama_kegiatan'   => ['required', 'max:255'],
            'province_id'   => ['required'],
            'city_id'   => ['required'],
            'subdistrict_id'   => ['required'],
            'kelurahan_id'   => ['required'],
            'rt_id'   => ['required'],
            'rw_id'   => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'kat_kegiatan_id.required' => 'Kategori Kegiatan Tidak Boleh Kosong !',
            'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong !',
            'province_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'city_id.required' => 'Kota Kabupaten Tidak Boleh Kosong !',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
