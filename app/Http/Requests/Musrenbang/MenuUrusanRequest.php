<?php

namespace App\Http\Requests\Musrenbang;

use Illuminate\Foundation\Http\FormRequest;

class MenuUrusanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [ 'nama_jenis'  => ['required']];
    }

    public function messages()
    {
        return [ 'nama_jenis.required' => 'Nama Jenis Tidak Boleh Kosong !'];
    }
}
