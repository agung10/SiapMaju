<?php

namespace App\Http\Requests\PBB;

use Illuminate\Foundation\Http\FormRequest;

class PembagianRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'blok_id'             => ['required'],
            'anggota_keluarga_id' => ['required'],
            'nop'                 => ['required'],
            'tgl_terima'          => ['required'],
            'foto_terima'         => ['mimes:jpeg,png,jpg', 'max:10000']
        ];

        return $validations;
    }
}
