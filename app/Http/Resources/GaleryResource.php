<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GaleryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'galeri_id' => $this->galeri_id,
            'agenda' => $this->nama_agenda,
            'detail_galeri' => $this->detail_galeri,
            'image_cover' => $this->image_cover ? asset('upload/galeri/'.$this->image_cover) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
