<?php

namespace App\Http\Requests\Polling;
use Illuminate\Foundation\Http\FormRequest;

class PertanyaanRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        $validations = [
            'province_id' => ['required'],
            'city_id' => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id' => ['required'],
            'rw_id' => ['required'],
            'rt_id' => ['required'],
            'close_date' => ['required'],
            'isi_pertanyaan' => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'province_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'city_id.required' => 'Kota Tidak Boleh Kosong !',
            'subdistrict_id.required' => 'Kabupaten Tidak Boleh Kosong !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
            'close_date.required' => 'Tanggal Penutupan Tidak Boleh Kosong !',
            'isi_pertanyaan.required' => 'Isi Pertanyaan Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}