<?php

namespace App\Http\Controllers;

use App\Models\LogoKabupaten;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use Illuminate\Http\Request;

class LogoKabupatenController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Logo.Kabupaten.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = $this->rajaOngkir->getCities();

        $resultCity = '<option></option>';
        foreach ($cities as $res) {
            $resultCity .= '<option value="' . $res->city_id . '">' . $res->type . ' ' . $res->city_name . '</option>';
        }

        return view('Logo.Kabupaten.create', compact('resultCity'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();
        try {
            if ($request->hasFile('logo')) {
                $input['logo'] = 'kabupaten' . rand() . '.' . $request->logo->getClientOriginalExtension();
                $request->logo->move(public_path('uploaded_files/logo'), $input['logo']);
            }
            LogoKabupaten::create($input);
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
        $data = LogoKabupaten::select(
            'logo',
            'nama_kabupaten',
        )
            ->findOrFail($id);

        return view('Logo.Kabupaten.show', compact('data'));
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
        $data = LogoKabupaten::findOrFail($id);

        $cities = $this->rajaOngkir->getCities();
        $resultCity = '<option disabled selected>Pilih Kota/Kabupaten</option>';
        foreach ($cities as $res) {
            $resultCity .= '<option value="' . $res->city_id . '"' . ((!empty($res->city_id)) ? ((!empty($res->city_id == $data->city_id)) ? ('selected') : ('')) : ('')) . '>' . $res->type . ' ' . $res->city_name . '</option>';
        }
        return view('Logo.Kabupaten.edit', compact('data', 'resultCity'));
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
        $dataKab = LogoKabupaten::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();
        try {
            if ($request->hasFile('logo')) {
                $input['logo'] = 'kabupaten' . rand() . '.' . $request->logo->getClientOriginalExtension();
                $request->logo->move(public_path('uploaded_files/logo'), $input['logo']);

                \File::delete(public_path('uploaded_files/logo/' . $dataKab->logo));
            }
            $dataKab->update($input);
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
        $dataKab = LogoKabupaten::findOrFail($id);

        try {
            if ($dataKab->logo) {
                \File::delete(public_path('uploaded_files/logo/' . $dataKab->logo));
            }
            LogoKabupaten::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new LogoKabupaten, 'datatableButtons') ? LogoKabupaten::datatableButtons() : ['show', 'edit', 'destroy'];
        $data = \DB::table('logo_kabupaten')->orderBy('updated_at', 'desc')->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('logo', function ($model) {
                if ($model->logo) {
                    return '<img src=' . asset('uploaded_files/logo/' . $model->logo) . ' width="70">';
                } else {
                    return '<img src=' . asset('images/NoPic.png') . ' width="70">';
                }
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('LogoKabupaten' . '.show', \Crypt::encryptString($data->logo_kabupaten_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('LogoKabupaten' . '.edit', \Crypt::encryptString($data->logo_kabupaten_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->logo_kabupaten_id]
                ]);
            })
            ->rawColumns(['logo', 'action'])
            ->make(true);
    }
}
