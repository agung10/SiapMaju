<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UmkmProdukSiapDipesan implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rwId;
    public $umkmName;
    public $umkmProdukId;
    public $umkmProdukName;

    public function __construct($rwId, $umkmName, $umkmProdukId, $umkmProdukName)
    {
        $this->rwId           = $rwId;
        $this->umkmName       = $umkmName;
        $this->umkmProdukId   = $umkmProdukId;
        $this->umkmProdukName = $umkmProdukName;
    }

    public function broadcastOn()
    {
        return new Channel('produk');
    }
}
