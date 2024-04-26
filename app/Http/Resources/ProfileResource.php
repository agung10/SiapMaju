<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'profile_id' => $this->profile_id,
            'isi_profile' => $this->isi_profile,
            'gambar_profile' => $this->gambar_profile ? asset('upload/profile/'.$this->gambar_profile) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
