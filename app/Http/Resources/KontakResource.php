<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KontakResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'kontak_id' => $this->kontak_id,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'mobile' => $this->mobile,
            'nama_lokasi' => $this->nama_lokasi,
            'email' => $this->email,
            'web' => $this->web,
            'rekening' => $this->rekening,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
