<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'nama_transaksi'   => ['required','max:30']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_transaksi.required' => 'Nama Transaksi Tidak Boleh Kosong !',
            'nama_transaksi.max' => 'Nama Transaksi Maksimal 30 Karakter !'
        ];
        
        return $returns;
    }
}
