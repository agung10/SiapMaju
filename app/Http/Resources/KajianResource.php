<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KajianResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'kajian_id' => $this->kajian_id,
            'kajian' => $this->kajian,
            'judul' => $this->judul,
            'author' => $this->author,
            'upload_materi_1' => $this->upload_materi_1 ? asset('upload/kajian/'.$this->upload_materi_1) 
                                                        : asset('images/noImage.jpg'),
            'upload_materi_2' => $this->upload_materi_2 ? asset('upload/kajian/'.$this->upload_materi_2) 
                                                        : asset('images/noImage.jpg'),
            'upload_materi_3' => $this->upload_materi_3 ? asset('upload/kajian/'.$this->upload_materi_3) 
                                                        : asset('images/noImage.jpg'),
            'upload_materi_4' => $this->upload_materi_4 ? asset('upload/kajian/'.$this->upload_materi_4) 
                                                        : asset('images/noImage.jpg'),
            'upload_materi_5' => $this->upload_materi_5 ? asset('upload/kajian/'.$this->upload_materi_5) 
                                                        : asset('images/noImage.jpg'),
            'image' => $this->image ? asset('upload/kajian/image/'.$this->image) : null,
            'kategori' => $this->kategori,
            'date_updated' => $this->date_updated,
            'user_udated' => $this->user_udated,
            'created_at' => $this->created_at,   
            'updated_at' => $this->updated_at,   
        ];  
    }
}
