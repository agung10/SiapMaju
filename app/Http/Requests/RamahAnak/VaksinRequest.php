<?php

namespace App\Http\Requests\RamahAnak;
use Illuminate\Foundation\Http\FormRequest;

class VaksinRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        $validations = [
            'nama_vaksin' => ['required']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'nama_vaksin.required' => 'Nama Vaksin Tidak Boleh Kosong !'
        ];
        
        return $returns;
    }
}