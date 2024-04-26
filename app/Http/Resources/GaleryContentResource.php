<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GaleryContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'galeri_id' => $this->galeri_id,
            'galeri' => $this->detail_galeri,
            'agenda' => $this->nama_agenda,
            'keterangan' => $this->keterangan,
            'kategori_file' => $this->kategori_file,
            'file' => $this->file ? asset('upload/galeri/konten/'.$this->file) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
