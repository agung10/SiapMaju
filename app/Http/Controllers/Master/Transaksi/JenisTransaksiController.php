<?php

namespace App\Http\Controllers\Master\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Master\MasterRepository;
use App\Models\Master\Transaksi\JenisTransaksi;

class JenisTransaksiController extends Controller
{
    public function __construct(MasterRepository $_Master)
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));

        $this->master = $_Master;
    }

    public function index()
    {
        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        $kegiatan = \DB::table('kegiatan')
                    ->select('kegiatan.kegiatan_id', 'kegiatan.nama_kegiatan')
                    ->get();

        $result = '<option selected disabled>Pilih Kegiatan</option>';
        foreach ($kegiatan as $myKegiatan) {
            $result .= '<option value="'.$myKegiatan->kegiatan_id.'">'.$myKegiatan->nama_kegiatan.'</option>';
        }
        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3, compact('result'));
    }

    public function store(Request $request, JenisTransaksi $model)
    {
        $rules = ['kegiatan_id' => 'required',
                  'nama_jenis_transaksi' => 'required',
                  'satuan' => 'required'];

        return $this->master->store($request, $model);
    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = JenisTransaksi::select('jenis_transaksi.jenis_transaksi_id',
                                        'jenis_transaksi.nama_jenis_transaksi',
                                        'jenis_transaksi.satuan',
                                        'jenis_transaksi.nilai',
                                        'kegiatan.nama_kegiatan as nama_kegiatan')
        ->join('kegiatan', 'kegiatan.kegiatan_id', 'jenis_transaksi.kegiatan_id')
        ->findOrFail($id);

        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3, compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = JenisTransaksi::select('jenis_transaksi.jenis_transaksi_id',
                                        'jenis_transaksi.nama_jenis_transaksi',
                                        'jenis_transaksi.satuan',
                                        'jenis_transaksi.nilai',
                                        'jenis_transaksi.kegiatan_id',
                                        'kegiatan.nama_kegiatan as nama_kegiatan')
        ->join('kegiatan', 'kegiatan.kegiatan_id', 'jenis_transaksi.kegiatan_id')
        ->findOrFail($id);

        $kegiatan = \DB::table('kegiatan')
                    ->select('kegiatan.kegiatan_id', 'kegiatan.nama_kegiatan')
                    ->get();

        $result = '<option selected disabled>Pilih Kegiatan</option>';
        foreach ($kegiatan as $myKegiatan) {
            $result .= '<option value="'.$myKegiatan->kegiatan_id.'"'.((!empty($myKegiatan->kegiatan_id)) ? ((!empty($myKegiatan->kegiatan_id == $data->kegiatan_id)) ? ('selected') : ('')) : ('')).'>'.$myKegiatan->nama_kegiatan.'</option>';
        }


        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3, compact('data', 'result'));
    }

    public function update(Request $request, $id, JenisTransaksi $model)
    {   
        $id = \Crypt::decryptString($id);
        
        $rules = ['kegiatan_id' => 'required',
                  'nama_jenis_transaksi' => 'required',
                  'satuan' => 'required'];

        return $this->master->update($id, $request, $rules, $model);
    }

    public function destroy($id, JenisTransaksi $model)
    {
        return $this->master->destroy($id, $model);
    }

     public function dataTables()
    {
        $datatableButtons = method_exists(new JenisTransaksi, 'datatableButtons') ? JenisTransaksi::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = JenisTransaksi::select('jenis_transaksi.jenis_transaksi_id',
                                        'jenis_transaksi.nama_jenis_transaksi',
                                        'jenis_transaksi.satuan',
                                        'kegiatan.nama_kegiatan as nama_kegiatan')
        ->join('kegiatan', 'kegiatan.kegiatan_id', 'jenis_transaksi.kegiatan_id')
        ->orderBy('jenis_transaksi_id','desc')
        ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Master.JenisTransaksi'.'.show', \Crypt::encryptString($data->jenis_transaksi_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.JenisTransaksi'.'.edit', \Crypt::encryptString($data->jenis_transaksi_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->jenis_transaksi_id : null
            ]);
        })->rawColumns(['action'])
        ->make(true);
    }
}
