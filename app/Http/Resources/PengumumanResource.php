<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PengumumanResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'pengumuman_id' => $this->pengumuman_id,
            'pengumuman' => $this->pengumuman,
            'image1' => $this->image1 ? asset('upload/pengumuman/'.$this->image1) : null,
            'image2' => $this->image2 ? asset('upload/pengumuman/'.$this->image2) : null,
            'image3' => $this->image3 ? asset('upload/pengumuman/'.$this->image3) : null,
            'tanggal' => $this->tanggal,
            'aktif' => $this->aktif,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'user_updated' => $this->user_updated,
        ];
    }
}
