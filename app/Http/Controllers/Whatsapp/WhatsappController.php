<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\WhatsappKeyRepository;
use GuzzleHttp\Client;

class WhatsappController extends Controller
{
    public function __construct(WhatsappKeyRepository $_WhatsappKeyRepository)
    {
        $route_name = explode('.', \Route::currentRouteName());
        $this->route1 = $route_name[0] ?? '';
        $this->route2 = $route_name[1] ?? '';
        $this->route3 = $route_name[2] ?? '';

        $this->whatsapp = $_WhatsappKeyRepository;
    }

    public function index()
    {
        $request     = @get_headers('https://dev2.kamarkerja.com:3333/instance/init');
        $whatsappKey = $request ? \Http::get('https://dev2.kamarkerja.com:3333/instance/init')->json()['key'] : '';
        // wait 2 second to init qrcode base64
        sleep(2);
        $src         = $request ? \Http::get("https://dev2.kamarkerja.com:3333/instance/qrbase64?key=$whatsappKey")->object()->qrcode : '';
        $hmtlImg     = $request ? "<img src='$src' alt='whatsapp-qr' class='qrcode'>" : '<p class="error-msg">Maaf terjadi kesalahan</p>';

        return view($this->route1 . '.' . $this->route2 . '.' . $this->route3, compact('hmtlImg', 'whatsappKey'));
    }

    public function create_test()
    {
        return $this->whatsapp->sendTest();
    }

    public function store(Request $request)
    {
        return $this->whatsapp->storeKey($request);
    }
}
