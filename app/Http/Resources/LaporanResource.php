<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LaporanResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'laporan_id' => $this->laporan_id,
            'kat_laporan' => $this->nama_kategori,
            'agenda' => $this->nama_agenda,
            'laporan' => $this->laporan,
            'detail_laporan' => $this->detail_laporan,
            'upload_materi' => $this->upload_materi ? asset('upload/laporan/materi/'.$this->upload_materi) : null,
            'image' => $this->image ? asset('upload/laporan/'.$this->image) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
