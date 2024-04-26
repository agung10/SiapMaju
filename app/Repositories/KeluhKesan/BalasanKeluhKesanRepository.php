<?php

namespace App\Repositories\KeluhKesan;

use App\Models\KeluhKesan\BalasKeluhKesan;
use App\Repositories\BaseRepository;

class BalasanKeluhKesanRepository extends BaseRepository
{
    public function __construct(BalasKeluhKesan $balasan)
    {
        $this->model = $balasan;
    }
}