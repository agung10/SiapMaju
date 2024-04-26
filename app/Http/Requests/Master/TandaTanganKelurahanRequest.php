<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TandaTanganKelurahanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'tanda_tangan_kelurahan' => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'tanda_tangan_kelurahan.mimes' => 'Format Tanda Tangan Kelurahan Harus JPEG,PNG,JPG,MPEG !',
            'tanda_tangan_kelurahan.max' => 'Ukuran Tanda Tangan Kelurahan Maximal 5MB !',
            'province_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'city_id.required' => 'Kota/Kabupaten Tidak Boleh Kosong !',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
