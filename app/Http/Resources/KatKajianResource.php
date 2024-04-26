<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KatKajianResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'kat_kajian_id' => $this->kat_kajian_id,
            'kategori' => $this->kategori,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'user_updated' => $this->user_updated
        ];
    }
}
