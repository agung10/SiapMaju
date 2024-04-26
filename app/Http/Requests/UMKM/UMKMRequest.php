<?php

namespace App\Http\Requests\UMKM;

use Illuminate\Foundation\Http\FormRequest;

class UMKMRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $edit = \Request::segment(3) !== 'create';

        return [
            'anggota_keluarga_id' => ['required', 'max:4'],
            'image'               => $edit ? ['max:5000', 'mimes:png,jpg,jpeg']
                                    : ['required', 'max:5000', 'mimes:png,jpg,jpeg'],
            'nama'                => ['required', 'max:150'],
            'deskripsi'           => ['required'],
            'aktif'               => ['required'],
            'disetujui'           => ['required'],
        ];
    }

    public function messages()
    {
        $returns = [
            'anggota_keluarga_id.required' => 'Owner wajib diisi',
            'image.required'               => 'Logo wajib diisi',
            'image.mimes'                  => 'Format Logo Harus PNG,JPG,JPEG !',
            'image.max'                    => 'Ukuran Logo Maximal 5MB !',
            'nama.required'                => 'Nama UMKM wajib diisi',
            'nama.max'                     => 'Nama UMKM Maksimal 150 Karakter',
            'deskripsi.required'           => 'Deskripsi UMKM wajib diisi',
            'aktif.required'               => 'Status wajib diisi',
            'disetujui.required'           => 'Persetujuan wajib diisi',
        ];

        return $returns;
    }
}
