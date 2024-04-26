<?php

namespace App\Http\Requests\UMKM;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\FormattedNumericMax;

class UmkmProdukRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $edit = \Request::segment(3) !== 'create';

        return [
            'umkm_id'          => ['required', 'max:4'],
            'image'            => $edit ? ['max:5000', 'mimes:png,jpg,jpeg']
                                : ['required', 'max:5000', 'mimes:png,jpg,jpeg'],
            'nama'             => ['required', 'max:150'],
            'umkm_kategori_id' => ['required', 'max:150'],
            'deskripsi'        => ['required'],
            'url'              => ['nullable', 'url', 'max:255'],
            'harga'            => ['required', new FormattedNumericMax(10)],
            'stok'             => ['required', 'max:5'],
            'berat'            => ['required'],
            'aktif'            => ['required'],
            'file_image_1'     => ['max:5000', 'mimes:png,jpg,jpeg'],
            'file_image_2'     => ['max:5000', 'mimes:png,jpg,jpeg'],
            'file_image_3'     => ['max:5000', 'mimes:png,jpg,jpeg']
        ];
    }

    public function messages()
    {
        $returns = [        
            'umkm_id.required'          => 'UMKM wajib diisi',
            'image.required'            => 'Logo wajib diisi',
            'image.max'                 => 'Ukuran Gambar Produk Maximal 5MB !',
            'image.mimes'               => 'Format Gambar Produk Harus PNG,JPG,JPEG !',
            'nama.required'             => 'Nama Produk wajib diisi',
            'nama.max'                  => 'Nama Produk Maksimal 150 Karakter',
            'umkm_kategori_id.required' => 'Kategori Produk wajib diisi',
            'umkm_kategori_id.max'      => 'Kategori Produk Maksimal 150 Karakter',
            'deskripsi.required'        => 'Deskripsi Produk wajib diisi',
            'url.url'                   => 'Format URL tidak valid. contoh valid url: https://example.com',
            'url.max'                   => 'Url Maksimal 255 Karakter',
            'harga.required'            => 'Harga wajib diisi',
            'stok.required'             => 'Stok wajib diisi',
            'stok.max'                  => 'Stok Maksimal 5 Karakter',
            'berat.required'            => 'Berat wajib diisi',
            'aktif.required'            => 'Status wajib diisi',
            'file_image_1.max'          => 'Ukuran Gambar Produk Maximal 5MB !',
            'file_image_1.mimes'        => 'Format Gambar Produk Harus PNG,JPG,JPEG !',
            'file_image_2.max'          => 'Ukuran Gambar Produk Maximal 5MB !',
            'file_image_2.mimes'        => 'Format Gambar Produk Harus PNG,JPG,JPEG !',
            'file_image_3.max'          => 'Ukuran Gambar Produk Maximal 5MB !',
            'file_image_3.mimes'        => 'Format Gambar Produk Harus PNG,JPG,JPEG !',
        ];

        return $returns;
    }
}
