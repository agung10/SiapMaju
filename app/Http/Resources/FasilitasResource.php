<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FasilitasResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'fasilitas_id'   => $this->fasilitas_id,
            'nama_fasilitas' => $this->nama_fasilitas,
            'lokasi'         => $this->lokasi,
            'keterangan'     => $this->keterangan,
            'pict1'          => $this->pict1 ? asset('uploaded_files/fasilitas/'.$this->pict1) 
                                             : asset('images/sikad.png'),
            'pict2'          => $this->pict2 ? asset('uploaded_files/fasilitas/'.$this->pict2) : null,
            'created_at'     => $this->created_at,   
            'updated_at'     => $this->updated_at,   
        ];  
    }
}
