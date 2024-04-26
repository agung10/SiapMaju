<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PengurusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pengurus_id' => $this->pengurus_id,
            'kat_pengurus' => $this->nama_kategori,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'no_urut' => $this->no_urut,
            'photo' => $this->photo ? asset('upload/pengurus/'.$this->photo) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
