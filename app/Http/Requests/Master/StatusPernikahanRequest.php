<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StatusPernikahanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
  
        $validations = [
            'nama_status_pernikahan'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_status_pernikahan.required' => 'Nama Status Pernikahan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
