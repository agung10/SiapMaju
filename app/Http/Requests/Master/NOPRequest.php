<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class NOPRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nop' => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'nop.required' => 'NOP Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
