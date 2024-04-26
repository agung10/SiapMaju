<?php

namespace App\Http\Requests\Musrenbang;

use Illuminate\Foundation\Http\FormRequest;

class BidangUrusanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [ 'nama_bidang'  => ['required']];
    }

    public function messages()
    {
        return [ 'nama_bidang.required' => 'Nama Bidang Tidak Boleh Kosong !'];
    }
}
