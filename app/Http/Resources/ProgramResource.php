<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'program_id' => $this->program_id,
            'nama_program' => $this->nama_program,
            'program' => $this->program,
            'pic' => $this->pic,
            'tanggal' => $this->tanggal,
            'image' => $this->image ? asset('upload/program/'.$this->image) : null,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
