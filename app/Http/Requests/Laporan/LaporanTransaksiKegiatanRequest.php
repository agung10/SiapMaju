<?php

namespace App\Http\Requests\Laporan;

use Illuminate\Foundation\Http\FormRequest;

class LaporanTransaksiKegiatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'kat_kegiatan_id'   => ['required'],
            'kegiatan_id'   => ['required'],
            'start_date'   => ['required'],
            'end_date'   => ['required']
        ];
    
        return $validations;
    }

    public function messages() {
        $returns = [
            'kat_kegiatan_id.required' => 'Kategori Kegiatan Tidak Boleh Kosong !',
            'kegiatan_id.required' => 'Kegiatan Tidak Boleh Kosong !',
            'start_date.required' => 'Tanggal awal Tidak Boleh Kosong !',
            'end_date.required' => 'Tanggal akhir Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}
