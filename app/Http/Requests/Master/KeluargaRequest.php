<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use App\Models\Master\Keluarga\AnggotaKeluarga;

class KeluargaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $updateRouteID = $this->route()->id ?? null;
        $editRouteID = $this->route()->ListKeluarga ?? null;
        $encryptID = null;

        if ($updateRouteID) {
            $encryptID = $updateRouteID;
        }

        if ($editRouteID) {
            $encryptID = $editRouteID;
        }

        $id = $encryptID ? \Crypt::decryptString($encryptID) : null;

        $anggotaKeluargaId = $id 
            ? AnggotaKeluarga::where('keluarga_id', $id)->where('hub_keluarga_id', 1)->first()->anggota_keluarga_id 
            : null;
            
        $userId = $anggotaKeluargaId 
            ? User::where('anggota_keluarga_id', $anggotaKeluargaId)->first()->user_id 
            : null;

        $validations = [
            'province_id'     => ['required'],
            'city_id'         => ['required'],
            'subdistrict_id'  => ['required'],
            'kelurahan_id'    => ['required'],
            'rw_id'           => ['required'],
            'rt_id'           => ['required'],
            'blok_id'         => ['required'],
            'alamat'          => ['required', 'max:500'],
            'no_telp'         => ['required', 'max:50'],
            // 'email'           => $anggotaKeluargaId ?
            //                         [
            //                             'required','email', 'max:150', 
            //                             'unique:users,email,'. $userId .',user_id',
            //                             'unique:anggota_keluarga,email,'. $anggotaKeluargaId .',anggota_keluarga_id'
            //                         ]
            //                         :
            //                         [
            //                             'required','email', 'max:150', 
            //                             'unique:users,email',
            //                             'unique:anggota_keluarga,email'
            //                         ],
            'email'          => ['required', 'max:50', 'email'],
            'status_domisili' => ['required'],
            'alamat_ktp'      => ['required', 'max:500'],
            'file'            => ['max:5000', 'mimes:png,jpg,jpeg,pdf'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'province_id.required' => 'Provinsi Tidak Boleh Kosong',
            'city_id.required' => 'Kota Tidak Boleh Kosong',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong',
            'rw_id.required' => 'RW Tidak Boleh Kosong',
            'rt_id.required' => 'RT Tidak Boleh Kosong',
            'blok_id.required' => 'Blok Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Domisili Tidak Boleh Kosong',
            'alamat.max' => 'Alamat Domisili Maksimal 500 Karakter',
            'no_telp.required' => 'No Telefon Tidak Boleh Kosong',
            'no_telp.max' => 'No Telefon Maksimal 50 Karakter',
            'email.required' => 'Email Tidak Boleh Kosong!',
            'email.max' => 'Email Maksimal 50 Karakter!',
            'email.email' => 'Format Email Salah!',
            // 'email.unique' => 'Email sudah digunakan!',
            'status_domisili.required' => 'Status Domisili Tidak Boleh Kosong!',
            'alamat_ktp.required' => 'Alamat Lain Tidak Boleh Kosong',
            'alamat_ktp.max' => 'Alamat Lain Maksimal 500 Karakter',
            'file.max'      => 'Ukuran File Maximal 5MB !',
            'file.mimes'    => 'Format File Harus PNG,JPG,JPEG,PDF !',
        ];

        return $returns;
    }
}
