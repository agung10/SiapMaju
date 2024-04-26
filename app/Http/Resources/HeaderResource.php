<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HeaderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'header_id' => $this->header_id,
            'image' => $this->image ? asset('upload/header/'.$this->image) : null,
            'judul' => $this->judul,
            'keterangan' => $this->keterangan,
            'date_updated' => $this->date_updated,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
