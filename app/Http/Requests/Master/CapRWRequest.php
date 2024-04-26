<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class CapRWRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'cap_rw' => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'rw_id'  => ['required'],
            'kelurahan_id'  => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'cap_rw.mimes' => 'Format Cap RW Harus JPEG,PNG,JPG,MPEG !',
            'cap_rw.max' => 'Ukuran Cap RW Maximal 5MB !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'kelurahan.required' => 'Kelurahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
