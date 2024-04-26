<?php

namespace App\Http\Requests\Master\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class JenisTransaksiRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'kegiatan_id'   => ['required'],
            'nama_jenis_transaksi'   => ['required', 'max:255'],
            'satuan'   => ['required', 'max:255'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'kegiatan_id.required' => 'Kegiatan Tidak Boleh Kosong !',
            'nama_jenis_transaksi.required' => 'jenis Transaksi Tidak Boleh Kosong!',
            'nama_jenis_transaksi.max' => 'jenis Transaksi Tidak Boleh Lebih Dari 255 Karakter !',
            'satuan.required' => 'satuan Tidak Boleh Kosong!',
            'satuan.max' => 'satuan Tidak Boleh Lebih Dari 255 Karakter !',
        ];
        
        return $returns;
    }
}
