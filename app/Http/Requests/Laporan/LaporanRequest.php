<?php

namespace App\Http\Requests\Laporan;

use Illuminate\Foundation\Http\FormRequest;

class LaporanRequest extends FormRequest
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
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required'],
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],
            'upload_materi'  => $isEdit 
                                    ? ['mimes:jpeg,png,jpg,pdf', 'max:10000']
                                    : ['required','mimes:jpeg,png,jpg,pdf', 'max:10000'],
            'image'          => $isEdit 
                                    ? ['mimes:jpeg,png,jpg', 'max:10000']
                                    : ['required','mimes:jpeg,png,jpg', 'max:10000'],
            'agenda_id'      => ['required'],
            'kat_laporan_id' => ['required'],
            'laporan'        => ['required', 'max:255'],
            'detail_laporan' => ['required', 'max:255'],
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

            'upload_materi.mimes' => 'File upload materi yang diperbolehkan hanya berupa gambar atau pdf',
            'upload_materi.max' => 'Ukuran file upload materi Maximal 10MB !',
            'image.required' => 'Cover wajib diisi',
            'image.mimes' => 'Format Cover Harus JPEG,PNG,JPG !',
            'image.max' => 'Ukuran Cover Maximal 10MB !',
            'agenda_id.required' => 'Agenda Tidak Boleh Kosong !',
            'kat_laporan_id.required' => 'Kategori Laporan Tidak Boleh Kosong !',
            'judul.required' => 'Judul Tidak Boleh Kosong !',
            'keterangan.required' => 'Keterangan Tidak Boleh Kosong !',
            'detail_laporan.required' => 'Detail Laporan Tidak Boleh Kosong !',
            'detail_laporan.max' => 'Detail Laporan Maximal 255 Karakter !',
            'laporan.required' => 'Laporan Tidak Boleh Kosong !',
            'laporan.max' => 'Laporan Maximal 255 Karakter !'
        ];

        return $returns;
    }
}
