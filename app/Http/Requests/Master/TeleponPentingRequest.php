<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TeleponPentingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama_instansi'   => ['required', 'max:255'],
            'no_tlp'          => ['required'],
            'alamat'   => ['required', 'max:255'],
            'kelurahan_id'   => ['required'],
            'rw_id'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_instansi.required' => 'Nama Instansi Tidak Boleh Kosong !',
            'nama_instansi.max' => 'Nama Instansi Tidak Boleh Lebih Dari 255 Karakter !',
            'no_tlp.required' => 'No. Telepon Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Tidak Boleh Kosong !',
            'alamat.max' => 'Alamat Tidak Boleh Lebih Dari 255 Karakter !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
