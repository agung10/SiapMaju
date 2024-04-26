<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class GalleryContentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // $route = \Request::segment(4); 
        // $isEdit = $route === 'edit';

        $validations = [
            // 'file'   => $isEdit ? ['mimes:jpg,jpeg,png,mpeg,mp4,webm','max:100040']
            //                      : ['required','mimes:jpg,jpeg,png,mpeg,mp4,webm','max:100040'],
            'file'   => ['mimes:jpg,jpeg,png,mpeg,mp4,webm','max:100040'],
            'keterangan'   => ['required'],
            'kategori_file'   => ['required'],
        ];
    
        return $validations;

    }

    public function messages() {
        $returns = [
            'file.required' => 'Gambar/Video Tidak Boleh Kosong !',
            'file.mimes' => 'Format Gambar/Video Harus JPEG,PNG,JPG,MPEG,MP4,WEBM !',
            'file.max' => 'Ukuran Gambar/Video Maximal 100MB !',
            'kategori_file.required' => 'Kategori File Tidak Boleh Kosong !',
            'keterangan.required' => 'Keterangan Tidak Boleh Kosong !',
        ];
        
        return $returns;
    }
}
