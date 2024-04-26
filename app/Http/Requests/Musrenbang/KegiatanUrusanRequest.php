<?php

namespace App\Http\Requests\Musrenbang;

use Illuminate\Foundation\Http\FormRequest;

class KegiatanUrusanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [ 'nama_kegiatan'  => ['required']];
    }

    public function messages()
    {
        return [ 'nama_kegiatan.required' => 'Nama Kegiatan Tidak Boleh Kosong !'];
    }
}
