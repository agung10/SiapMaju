<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class CapRTRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'cap_rt' => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'rt_id'  => ['required'],
            'rw_id'  => ['required'],
            'kelurahan_id'  => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'cap_rt.mimes' => 'Format Cap RT Harus JPEG,PNG,JPG,MPEG !',
            'cap_rt.max' => 'Ukuran Cap RT Maximal 5MB !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'kelurahan.required' => 'Kelurahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
