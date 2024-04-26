<?php

namespace App\Http\Requests\Panduan;

use Illuminate\Foundation\Http\FormRequest;

class PanduanRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'panduan'   => ['required', 'mimes:pdf','max:100040']
        ];
    
        return $validations;

    }
}
