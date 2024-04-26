<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use App\Models\Master\Keluarga\AnggotaKeluarga;

class AddAnggotaKeluargaRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $anggotaKeluargaId = null;
        $id = $this->route()->AnggotaKeluarga;

        // edit page (id hashed)
        if( !is_null($id) && is_numeric($id) ) {
            $anggotaKeluargaId = $id;
        }

        // edit by ajax request (id not hashed)
        if( !is_null($id) && !is_numeric($id) ) {
            $anggotaKeluargaId = \Crypt::decryptString($id);
        }
        
        $userId = $anggotaKeluargaId 
            ? User::where('anggota_keluarga_id', $anggotaKeluargaId)->first()->user_id 
            : null;

        $validations = [
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required'],
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],

            'nama'            => ['required', 'max:255'],
            'tgl_lahir'       => ['required'],
            'mobile'          => ['required'],
            'keluarga_id'     => ['required'],
            'jenis_kelamin'   => ['required'],
            'hub_keluarga_id' => ['required'],
            'alamat'          => ['required', 'max:255'],
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
                                    
            'password'        => $anggotaKeluargaId ? ['nullable', 'min:6', 'max:255']: ['required', 'min:6', 'max:255']
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'province_id.required' => 'Provinsi Tidak Boleh Kosong !',
            'city_id.required' => 'Kota/Kabupaten Tidak Boleh Kosong !',
            'subdistrict_id.required' => 'Kecamatan Tidak Boleh Kosong !',
            'kelurahan_id.required' => 'Kelurahan Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',

            'nama.required' => 'Nama Tidak Boleh Kosong !',
            'nama.max' => 'Nama Maksimal 255 Karakter!',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong!',
            'mobile.required' => 'Mobile Tidak Boleh Kosong!',
            'jenis_kelamin.required' => 'Jenis Kelamin Tidak Boleh Kosong !',
            'keluarga_id.required' => 'Keluarga Tidak Boleh Kosong !',
            'hub_keluarga_id.required' => 'Hubungan Keluarga Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Tidak Boleh Kosong !',
            'alamat.max' => 'Alamat Maksimal 255 Karakter!',
            'email.required' => 'Email Tidak Boleh Kosong !',
            'email.max' => 'Email Maksimal 255 Karakter!',
            'email.email' => 'Format Email Salah!',
            // 'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password Tidak Boleh Kosong !',
            'password.max' => 'Password Maksimal 255 Karakter!'
        ];

        return $returns;
    }
}
