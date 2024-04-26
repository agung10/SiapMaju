<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'agenda_id' => $this->agenda_id,
            'program' => $this->nama_program,
            'nama_agenda' => $this->nama_agenda,
            'lokasi' => $this->lokasi,
            'tanggal' => $this->tanggal,
            'jam' => $this->jam,
            'agenda' => $this->agenda,
            'image' => $this->image ? asset('upload/agenda/'.$this->image) : null,
            'user_updated' => $this->user_updated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
