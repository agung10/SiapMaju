<?php

namespace App\Http\Requests\Agenda;

use Illuminate\Foundation\Http\FormRequest;

class AgendaKegiatanRequest extends FormRequest
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
            'image'   => $isEdit ? ['mimes:jpeg,png,jpg', 'max:10000']
                : ['required', 'mimes:jpeg,png,jpg', 'max:10000'],
            'nama_agenda'   => ['required'],
            'lokasi'   => ['required'],
            'tanggal'   => ['required'],
            'jam'   => ['required'],
            'agenda'   => ['required'],
            'program_id' => ['required']
        ];

        return $validations;
    }

    public function messages()
    {
        $returns = [
            'image.required' => 'Gambar Tidak Boleh Kosong !',
            'image.mimes' => 'Format Gambar Harus JPEG,PNG,JPG !',
            'image.max' => 'Ukuran Gambar Maximal 10MB !',
            'nama_agenda.required' => 'Nama Agenda Tidak Boleh Kosong !',
            'lokasi.required' => 'Lokasi Tidak Boleh Kosong !',
            'tanggal.required' => 'Tanggal Tidak Boleh Kosong !',
            'jam.required' => 'Jam Tidak Boleh Kosong !',
            'agenda.required' => 'Agenda Tidak Boleh Kosong !',
            'program_id.required' => 'Program Tidak Boleh Kosong !'
        ];

        return $returns;
    }
}
