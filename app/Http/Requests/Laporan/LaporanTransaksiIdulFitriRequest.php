<?php

namespace App\Http\Requests\Laporan;

use Illuminate\Foundation\Http\FormRequest;

class LaporanTransaksiIdulFitriRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'start_date'   => ['required'],
            'end_date'   => ['required']
        ];
    
        return $validations;
    }

    public function messages() {
        $returns = [
            'start_date.required' => 'Tanggal awal Tidak Boleh Kosong !',
            'end_date.required' => 'Tanggal akhir Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}
