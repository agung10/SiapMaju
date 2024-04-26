<?php

namespace App\Http\Controllers\KeluhKesan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\KeluhKesan\{ KeluhKesanRepository, BalasanKeluhKesanRepository };
use App\Repositories\RoleManagement\UserRepository;

class KeluhKesanController extends Controller
{
    public function __construct(KeluhKesanRepository $keluhKesan, UserRepository $user, BalasanKeluhKesanRepository $balasan)
    {
        $this->keluhKesan = $keluhKesan;
        $this->balasan    = $balasan;
        $this->user       = $user;
    }
    
    public function index()
    {
        $users = $this->user->orderBy('username')->get();

        return view ('keluh_kesan.keluh_kesan.index', compact('users'));
    }

    public function create()
    {
        $users = $this->user->orderBy('username')->get();

        return view ('keluh_kesan.keluh_kesan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $storeImage = true;
        $rules     = ['keluh_kesan' => 'required', 'user_id' => 'required'];
        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed', 
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $store = $this->keluhKesan->store($request->all(), $storeImage);

        return redirect()
                ->route('keluhKesan.keluhKesan.index')
                ->with(['notification' => 'Data berhasil disimpan', 'type' => 'success']);

    }

    public function show($keluhKesanId)
    {  
        $keluhKesan = $this->keluhKesan->withShow('balasan', $keluhKesanId);
        
        return view('keluh_kesan.keluh_kesan.show', compact('keluhKesan'));
    }

    public function getBalasan($balasanId)
    {  
        $balasan = $this->balasan->show($balasanId);

        return $balasan;
    }

    public function edit($keluhKesanId)
    {   
        $keluhKesan = $this->keluhKesan->withShow('balasan', $keluhKesanId);
        $users = $this->user->orderBy('username')->get();

        return view('keluh_kesan.keluh_kesan.edit', compact('keluhKesan', 'users'));
    }

    public function update(Request $request, $keluhKesanId)
    {
        $updateImage = $request->exists('file_image');
        $rules     = ['keluh_kesan' => 'required', 'user_id' => 'required'];
        $validator = \Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed', 
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $update = $this->keluhKesan->update($request->all(), $keluhKesanId, $updateImage);

        return redirect()
                ->route('keluhKesan.keluhKesan.index')
                ->with(['notification' => 'Data berhasil diupdate', 'type' => 'success']);
    }

    public function destroy($id)
    {
       $keluhKesan = $this->show($id);

       $balasans = $this->balasan->where('keluh_kesan_id', $id)->get();

        foreach ($balasans as $balasan) {
            $this->balasan->deleteFile('balas_keluh_kesan/' . $balasan->file_image);
            $deleteBalasan = $this->balasan->destroy($balasan->balas_keluh_kesan_id);
        }

       $this->keluhKesan->deleteFile('keluh_kesan/' . $keluhKesan->file_image);
       $this->keluhKesan->delete($id);

       return response()->json(['status' => 'success']);
    }

    public function dataTables(Request $request)
    {
        return $this->keluhKesan->dataTablesKeluhKesan($request);
    }

    public function storeBalasan(Request $request)
    {
        $storeImage = $request->exists('file_image');
        $store = $this->balasan->store($request->all(), $storeImage);

        return ['status' => $store];
    }

    public function updateBalasan(Request $request)
    {
        $updateImage = $request->exists('file_image');
        $balasanId = $request->balasan_id;
        $update = $this->balasan->update($request->except('balasan_id'), $balasanId, $updateImage);

        return ['status' => $update];
    }

    public function destroyBalasan(Request $request)
    {
       $balasan = $this->balasan->show($request->balasan_id);
       $this->balasan->deleteFile('balas_keluh_kesan/' . $balasan->file_image);
       $this->balasan->delete($balasan->balas_keluh_kesan_id);

       return response()->json(['status' => 'success']);
    }
}
