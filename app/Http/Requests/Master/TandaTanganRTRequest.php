<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TandaTanganRTRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'tanda_tangan_rt' => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'rt_id'  => ['required'],
            'rw_id'  => ['required'],
            'kelurahan_id'  => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'tanda_tangan_rt.mimes' => 'Format Tanda Tangan RT Harus JPEG,PNG,JPG,MPEG !',
            'tanda_tangan_rt.max' => 'Ukuran Tanda Tangan RT Maximal 5MB !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'kelurahan.required' => 'Kelurahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
