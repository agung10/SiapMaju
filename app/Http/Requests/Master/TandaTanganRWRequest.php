<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TandaTanganRWRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'tanda_tangan_rw' => ['mimes:jpg,jpeg,png,mpeg','max:5000'],
            'rw_id'  => ['required'],
            'kelurahan_id'  => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'tanda_tangan_rw.mimes' => 'Format Tanda Tangan RW Harus JPEG,PNG,JPG,MPEG !',
            'tanda_tangan_rw.max' => 'Ukuran Tanda Tangan RW Maximal 5MB !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'kelurahan.required' => 'Kelurahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
