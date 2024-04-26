<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KatLaporanResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'kat_laporan_id' => $this->kat_laporan_id,
            'nama_kategori' => $this->nama_kategori,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
