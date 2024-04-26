<?php

namespace App\Http\Requests\Musrenbang;

use Illuminate\Foundation\Http\FormRequest;

class UsulanUrusanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $route = \Request::segment(4);
        $isEdit = $route === 'edit';

        $validations = [
            'menu_urusan_id'     => ['required'],
            'bidang_urusan_id'   => ['required'],
            'kegiatan_urusan_id' => ['required'],
            'rt_id'              => ['required'],
            'rw_id'              => ['required'],
            'user_id'            => ['required'],
            'alamat'             => ['required'],
            'jumlah'             => ['required'],
            'tahun'              => ['required'],
            'status_usulan'      => ['required'],
            'keterangan'         => ['required'],
            'gambar_1'           => $isEdit ? ['mimes:jpeg,png,jpg', 'max:5000']
                                    : ['required', 'mimes:jpeg,png,jpg', 'max:5000'],
            'gambar_2'           => $isEdit ? ['mimes:jpeg,png,jpg', 'max:5000']
                                    : ['nullable', 'mimes:jpeg,png,jpg', 'max:5000'],
            'gambar_3'           => $isEdit ? ['mimes:jpeg,png,jpg', 'max:5000']
                                    : ['nullable', 'mimes:jpeg,png,jpg', 'max:5000'],
            'latitude'           => ['required'],
            'longitude'          => ['required'],
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'menu_urusan_id.required' => 'Jenis Usulan Tidak Boleh Kosong !',
            'bidang_urusan_id.required' => 'Bidang Tidak Boleh Kosong !',
            'kegiatan_urusan_id.required' => 'Kegiatan Tidak Boleh Kosong !',
            'rt_id.required' => 'RT Tidak Boleh Kosong !',
            'rw_id.required' => 'RW Tidak Boleh Kosong !',
            'user_id.required' => 'Ketua RW Tidak Boleh Kosong !',
            'alamat.required' => 'Alamat Tidak Boleh Kosong !',
            'jumlah.required' => 'Jumlah Tidak Boleh Kosong !',
            'tahun.required' => 'Tahun Tidak Boleh Kosong !',
            'status_usulan.required' => 'Status Usulan Tidak Boleh Kosong !',
            'keterangan.required' => 'Keterangan Tidak Boleh Kosong !',
            'gambar_1.required' => 'Gambar Wajib Tidak Boleh Kosong !',
            'gambar_1.mimes' => 'Format Gambar Wajib Harus JPEG,PNG,JPG !',
            'gambar_1.max' => 'Ukuran Gambar Wajib Maximal 5MB !',
            'gambar_2.required' => 'Gambar Tidak Boleh Kosong !',
            'gambar_2.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'gambar_2.max' => 'Ukuran Gambar Maximal 5MB !',
            'gambar_3.required' => 'Gambar Tidak Boleh Kosong !',
            'gambar_3.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'gambar_3.max' => 'Ukuran Gambar Maximal 5MB !',
            'latitude.required' => 'Latitude Tidak Boleh Kosong !',
            'longitude.required' => 'Longitude Tidak Boleh Kosong !',
        ];

        return $returns;
    }
}
