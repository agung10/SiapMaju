<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\WhatsappKey\WhatsappKey;

class WhatsappKeyRepository extends BaseRepository
{
    public function __construct(WhatsappKey $model)
    {
        $this->apiUrl = config('whatsapp.api_url');
        $this->model  = $model;
    }

    public function send($message, $destinationNumber)
    {
        $response = ['status' => false, 'msg' => ''];
        $endpoint = $this->apiUrl . 'message/text';
        $response = @get_headers($endpoint);

        // if cant reach url
        if (!$response) {
            $response['msg'] = 'Endpoint service whatsapp tidak dapat diakses';

            return $response;
        }

        $whatsappKey = $this->model
            ->select('whatsapp_key')
            ->first();

        if (!$whatsappKey) {
            $response['msg'] = 'No Whatsapp belum disandingkan';

            return $response;
        }

        $destinationNumber = '62' . substr($destinationNumber, 1);

        $waResponse = \Http::post("$endpoint?key=$whatsappKey->whatsapp_key", [
            'id' => $destinationNumber,
            'message' => $message
        ]);

        $response['status'] = $waResponse->status();
        $response['msg'] = $waResponse->status() ? 'Pesan berhasil terkirim' : 'Gagal mengirim pesan';

        return $response;
    }

    public function storeKey($request)
    {
        $transaction =  false;
        $whatasppKey = WhatsappKey::truncate();

        \DB::beginTransaction();

        try {
            WhatsappKey::create($request->all());
            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();

            throw $e;
        }

        $status = $transaction ? 'success' : 'failed';

        return response()->json(compact('status'));
    }

    public function sendTest()
    {
        $response     = ['status' => false, 'message' => null];
        $responseService = @get_headers($this->apiUrl);

        // if cant reach url
        if (!$responseService) {
            $response['msg'] = 'Endpoint service whatsapp tidak dapat diakses';

            return $response;
        }

        $key          = $this->model->first()->whatsapp_key;
        $endpointInfo = $this->apiUrl . 'instance/info?key=' . $key;
        $info         = \Http::get($endpointInfo)->object();
        $idRegistered = $info->instance_data->user->id;
        $phoneNumber  = $idRegistered;
        // Use regular expression to extract the number
        preg_match('/(\d+)/', $idRegistered, $extractedPhoneNumber);
        if (isset($extractedPhoneNumber[1])) {
            $phoneNumber = $extractedPhoneNumber[1];
        }

        $message        = 'TEST [Waktu:' . date('d F Y H:i:s') . ']';
        $endpointSendwa = $this->apiUrl . 'message/text';
        $waResponse = \Http::post("$endpointSendwa?key=$key", [
            'id' => $phoneNumber,
            'message' => $message
        ]);
        $response['status'] = $waResponse->successful();
        $response['msg'] = $waResponse->status() ? 'Pesan berhasil terkirim' : 'Gagal mengirim pesan';

        return $response;
    }
}
