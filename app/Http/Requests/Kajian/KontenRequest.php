<?php

namespace App\Http\Requests\Kajian;

use Illuminate\Foundation\Http\FormRequest;

class KontenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $validations = [
            'province_id'    => ['required'],
            'city_id'        => ['required'],
            'subdistrict_id' => ['required'],
            'kelurahan_id'   => ['required'],
            'rw_id'          => ['required'],
            'rt_id'          => ['required'],

            'kat_kajian_id'   => ['required'],
            'judul'           => ['required'],
            'kajian'          => ['required'],
            'author'          => ['required'],
            'upload_materi_1' => ['mimes:jpeg,png,jpg,doc,docx,pdf,mp4', 'max:10000'],
            'upload_materi_2' => ['mimes:jpeg,png,jpg,doc,docx,pdf,mp4', 'max:10000'],
            'upload_materi_3' => ['mimes:jpeg,png,jpg,doc,docx,pdf,mp4', 'max:10000'],
            'upload_materi_4' => ['mimes:jpeg,png,jpg,doc,docx,pdf,mp4', 'max:10000'],
            'upload_materi_5' => ['mimes:jpeg,png,jpg,doc,docx,pdf,mp4', 'max:10000'],
            'image'           => ['mimes:jpeg,png,jpg', 'max:10000'],
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
            
            'kat_kajian_id.required' => 'Kategori Peraturan Tidak Boleh Kosong !',
            'judul.required' => 'Judul Tidak Boleh Kosong !',
            'kajian.required' => 'Peraturan Tidak Boleh Kosong !',
            'author.required' => 'Penulis Tidak Boleh Kosong !',
            'upload_materi_1.mimes' => 'Format Gambar Harus JPEG,PNG,JPG,DOC,DOCX,PDF,MP4 !',
            'upload_materi_1.max' => 'Ukuran Gambar Maximal 10MB !',
            'upload_materi_2.mimes' => 'Format Gambar Harus JPEG,PNG,JPG,DOC,DOCX,PDF,MP4 !',
            'upload_materi_2.max' => 'Ukuran Gambar Maximal 10MB !',
            'upload_materi_3.mimes' => 'Format Gambar Harus JPEG,PNG,JPG,DOC,DOCX,PDF,MP4 !',
            'upload_materi_3.max' => 'Ukuran Gambar Maximal 10MB !',
            'upload_materi_4.mimes' => 'Format Gambar Harus JPEG,PNG,JPG,DOC,DOCX,PDF,MP4 !',
            'upload_materi_4.max' => 'Ukuran Gambar Maximal 10MB !',
            'upload_materi_5.mimes' => 'Format Gambar Harus JPEG,PNG,JPG,DOC,DOCX,PDF,MP4 !',
            'upload_materi_5.max' => 'Ukuran Gambar Maximal 10MB !',
            'image.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image.max' => 'Ukuran Gambar Maximal 10MB !',
        ];

        return $returns;
    }
}
