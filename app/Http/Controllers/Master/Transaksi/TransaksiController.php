<?php

namespace App\Http\Controllers\Master\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\helper;
use App\Models\Master\Transaksi\Transaksi;

class TransaksiController extends Controller
{
  
    public function __construct()
    {
        $route_name  = explode('.',\Route::currentRouteName());
        $this->route1 = ((isset($route_name[0])) ? $route_name[0] : (''));
        $this->route2 = ((isset($route_name[1])) ? $route_name[1] : (''));
        $this->route3 = ((isset($route_name[2])) ? $route_name[2] : (''));
       
    }

    public function index()
    {
        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3);
    }

    public function create()
    {
        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3);
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try{
            Transaksi::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = Transaksi::findOrFail($id);

        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3,compact('data'));
    }

    public function edit($id)
    {   
        $id = \Crypt::decryptString($id);
        $data = Transaksi::findOrFail($id);

        return view($this->route1.'.'.'Transaksi'.'.'.$this->route2.'.'.$this->route3,compact('data'));
    }

    public function update(Request $request, $id)
    {   
        $id = \Crypt::decryptString($id);
        
        $data = Transaksi::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try{
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = Transaksi::findOrFail($id);

        try {
            Transaksi::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new Transaksi, 'datatableButtons') ? Transaksi::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = Transaksi::select('transaksi.transaksi_id', 'transaksi.nama_transaksi')
                ->orderBy('transaksi_id', 'desc')
                ->get();
        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Master.Transaksi'.'.show', \Crypt::encryptString($data->transaksi_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Master.Transaksi'.'.edit', \Crypt::encryptString($data->transaksi_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->transaksi_id : null
            ]);
        })
        ->rawColumns(['action'])
        ->make(true);
    }
}
