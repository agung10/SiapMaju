<?php

namespace App\Http\Requests\PBB;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'tgl_bayar'   => ['required'],
            'bukti_bayar' => ['mimes:jpeg,png,jpg', 'max:10000']
        ];

        return $validations;
    }
}
