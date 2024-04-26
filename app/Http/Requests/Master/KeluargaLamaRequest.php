<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class KeluargaLamaRequest extends FormRequest
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

        $validations = [
            'warga_id'        => ['required'],
            'hub_keluarga_id' => ['required'],
            'no_telp'          => ['required', 'max:50'],
            'tgl_lahir'       => ['required'],

            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required'],
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],
            'blok_id'        => ['required'],
            'alamat'         => ['required', 'max:500'],
            'email'          => ['required', 'max:50', 'email'],
            'status_domisili'=> ['required'],
            'alamat_ktp'     => ['required', 'max:500'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'warga_id.required' => 'Warga Tidak Boleh Kosong',
            'hub_keluarga_id.required' => 'Hubungan Keluarga Tidak Boleh Kosong',
            'no_telp.required' => 'No Telepon Tidak Boleh Kosong',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',

            'province_id.required' => 'Provinsi Tidak Boleh Kosong',
            'city_id.required' => 'Kota Tidak Boleh Kosong',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong',
            'rw_id.required' => 'RW Tidak Boleh Kosong',
            'rt_id.required' => 'RT Tidak Boleh Kosong',
            'blok_id.required' => 'Blok Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Domisili Tidak Boleh Kosong',
            'alamat.max' => 'Alamat Domisili Maksimal 500 Karakter',
            'no_telp.required' => 'No Telepon Tidak Boleh Kosong',
            'no_telp.max' => 'No Telepon Maksimal 50 Karakter',
            'email.required' => 'Email Tidak Boleh Kosong!',
            'email.max' => 'Email Maksimal 50 Karakter!',
            'email.email' => 'Format Email Salah!',
            'email.unique' => 'Email sudah digunakan!',
            'status_domisili.required' => 'Status Domisili Tidak Boleh Kosong!',
            'alamat_ktp.required' => 'Alamat Lain Tidak Boleh Kosong',
            'alamat_ktp.max' => 'Alamat Lain Maksimal 500 Karakter',
        ];

        return $returns;
    }
}
