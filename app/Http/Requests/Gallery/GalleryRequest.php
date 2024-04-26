<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $validations = [
            'agenda_id'   => ['required'],
            'image_cover'   => ['mimes:jpg,jpeg,png,mpeg,mp4,webm','max:100040'],
            'detail_galeri'   => ['required','max:255']
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'agenda_id.required' => 'Agenda Tidak Boleh Kosong !',
            'image_cover.mimes' => 'Format Gambar/Video Harus JPEG,PNG,JPG,MPEG,MP4,WEBM !',
            'image_cover.max' => 'Ukuran Gambar/Video Maximal 100MB !',
            'detail_galeri.required' => 'Detail Gallery Tidak Boleh Kosong !',
            'detail_galeri.max' => 'Detail Gallery Maksimal 255 Karakter !'
        ];
        
        return $returns;
    }
}
