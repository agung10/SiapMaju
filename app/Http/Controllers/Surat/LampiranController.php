<?php

namespace App\Http\Controllers\Surat;

use App\Http\Controllers\Controller;
use App\Models\Surat\Lampiran;
use Illuminate\Http\Request;

class LampiranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Surat.Lampiran.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jenis_surat = \DB::table('jenis_surat')->select('jenis_surat_id', 'jenis_permohonan')->pluck('jenis_permohonan', 'jenis_surat_id');
        $resultSurat = '<option></option>';
        foreach ($jenis_surat as $jenis_surat_id => $jenis_permohonan) {
            $resultSurat .= '<option value="' . $jenis_surat_id . '">' . $jenis_permohonan . '</option>';
        }

        return view('Surat.Lampiran.create', compact('resultSurat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            Lampiran::create($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Lampiran::join('jenis_surat', 'jenis_surat.jenis_surat_id', 'lampiran.jenis_surat_id')->findOrFail($id);
        
        return view('Surat.Lampiran.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data = Lampiran::findOrFail($id);

        $jenis_surat = \DB::table('jenis_surat')->select('jenis_surat_id', 'jenis_permohonan')->pluck('jenis_permohonan', 'jenis_surat_id');
        $resultSurat = '<option></option>';
        foreach ($jenis_surat as $jenis_surat_id => $jenis_permohonan) {
            $resultSurat .= '<option value="' . $jenis_surat_id . '"' . ((!empty($jenis_surat_id)) ? ((!empty($jenis_surat_id == $data->jenis_surat_id)) ? ('selected') : ('')) : ('')) . '>' . $jenis_permohonan . '</option>';
        }

        return view('Surat.Lampiran.edit', compact('data', 'resultSurat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);
        $data = Lampiran::findOrFail($id);

        \DB::beginTransaction();
        try {
            $data->update($request->except('proengsoft_jsvalidation'));
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Lampiran::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $model = Lampiran::select(
            'lampiran.lampiran_id',
            'jenis_surat.jenis_permohonan',
            'lampiran.nama_lampiran',
            'lampiran.kategori',
            'lampiran.status'
        )
        ->join('jenis_surat', 'jenis_surat.jenis_surat_id', 'lampiran.jenis_surat_id');

        $datatableButtons = method_exists(new Lampiran, 'datatableButtons') ? Lampiran::datatableButtons() : ['show', 'edit', 'destroy'];

        return \DataTables::of($model)
            ->addIndexColumn()
            ->addColumn('action', function ($model) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show'         => in_array("show", $datatableButtons ) ? route('Surat.Lampiran'.'.show', \Crypt::encryptString($model->lampiran_id)) : null,
                    'edit'         => in_array("edit", $datatableButtons ) ? route('Surat.Lampiran'.'.edit', \Crypt::encryptString($model->lampiran_id)) : null,
                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $model->lampiran_id : null
                ]);
            })
            ->addColumn('kategori', function ($model) {
                if ($model->kategori) {
                    return '<span class="badge badge-primary">Wajib</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Wajib</span>';
                }
            })
            ->addColumn('status', function ($model) {
                if ($model->status) {
                    return '<span class="badge badge-primary">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
            })
            ->rawColumns(['action', 'kategori', 'status'])
            ->make(true);
    }
}
