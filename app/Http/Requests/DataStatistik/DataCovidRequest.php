<?php

namespace App\Http\Requests\DataStatistik;

use Illuminate\Foundation\Http\FormRequest;

class DataCovidRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'tgl_input'   => ['required'],
            'rt_id'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'tgl_input.required' => 'Tanggal Input Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
