<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan\Laporan;
use App\Models\Laporan\KatLaporan;
use App\Models\Agenda;
use App\Helpers\helper;
use App\Models\Master\Kelurahan;
use App\Models\Master\RT;
use App\Models\Master\RW;
use App\Repositories\RajaOngkir\RajaOngkirRepository;
use App\Repositories\RoleManagement\UserRepository;

class LaporanController extends Controller
{
    public function __construct(RajaOngkirRepository $rajaOngkir, UserRepository $_user)
    {
        $this->rajaOngkir = $rajaOngkir;
        $this->user = $_user;
    }

    public function index()
    {
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rt.rw_id', $rwID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRw) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRw) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        return view('Laporan.Laporan.index', compact('resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw'));
    }

    public function create()
    {
        $KatLaporan = KatLaporan::select('kat_laporan.kat_laporan_id', 'kat_laporan.nama_kategori')
            ->get();

        $resultKat = '<option disabled selected></option>';
        foreach ($KatLaporan as $myData) {
            $resultKat .= '<option value="' . $myData->kat_laporan_id . '">' . $myData->nama_kategori . '</option>';
        }

        $isRt = \helper::checkUserRole('rt');
        $isRw = \helper::checkUserRole('rw');
        $isAdmin = \helper::checkUserRole('admin');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
        )
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rt.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();

        $resultRT = '<option></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '">' . $res->rt . '</option>';
            }
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
        )
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw || $isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();

        $resultRW = '<option></option>';
        foreach ($rw as $res) {
            if ($isRw || $isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '">' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw || $isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();

        $resultKelurahan = '<option></option>';
        foreach ($kelurahan as $res) {
            if ($isRw || $isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '">' . $res->nama . '</option>';
            }
        }

        return view('Laporan.Laporan.create', compact('resultKat', 'resultRT', 'resultRW', 'resultKelurahan', 'isAdmin', 'isRw', 'isRt'));
    }

    public function store(Request $request)
    {
        $input = $request->except('proengsoft_jsvalidation');

        \DB::beginTransaction();

        try {

            if ($request->hasFile('image')) {
                $input['image'] = 'report' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/laporan'), $input['image']);
            }

            if ($request->hasFile('upload_materi')) {
                $input['upload_materi'] = 'materi' . rand() . '.' . $request->upload_materi->getClientOriginalExtension();
                $request->upload_materi->move(public_path('upload/laporan/materi'), $input['upload_materi']);
            }

            $input['user_created'] = \Auth::user()->user_id;

            Laporan::create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        $id = \Crypt::decryptString($id);
        $data =  Laporan::find($id);
        $laporanGetAlamat = $this->getAlamat($id);

        return view('Laporan.Laporan.show', compact('data', 'laporanGetAlamat'));
    }

    public function edit($id)
    {
        $id = \Crypt::decryptString($id);
        $data =  Laporan::find($id);

        $KatLaporan = KatLaporan::select('kat_laporan.kat_laporan_id', 'kat_laporan.nama_kategori')->get();

        $result = '<option disabled selected></option>';
        foreach ($KatLaporan as $myData) {
            $result .= '<option value="' . $myData->kat_laporan_id . '" ' . ((!empty($myData->kat_laporan_id)) ? (($data->kat_laporan_id == $myData->kat_laporan_id) ? ('selected') : ('')) : ('')) . '>' . $myData->nama_kategori . '</option>';
        }

        $agenda = Agenda::select('agenda.agenda_id', 'agenda.nama_agenda')->get();

        $resultAgenda = '<option disabled selected></option>';
        foreach ($agenda as $myAgenda) {
            $resultAgenda .= '<option value="' . $myAgenda->agenda_id . '" ' . ((!empty($myAgenda->agenda_id)) ? (($data->agenda_id == $myAgenda->agenda_id) ? ('selected') : ('')) : ('')) . '>' . $myAgenda->nama_agenda . '</option>';
        }

        $isAdmin = \helper::checkUserRole('admin');
        $isRw = \helper::checkUserRole('rw');
        $isRt = \helper::checkUserRole('rt');

        $getData = $this->user->currentUser();
        $kelurahanID = $getData->kelurahan_id;
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $rt = RT::select(
            'rt.rt',
            'rt.rt_id',
            'rt.rw_id',
            'rt.kelurahan_id'
        )
            ->where('rt.rw_id', $data->rw_id)
            ->join('rw', 'rw.rw_id', 'rt.rw_id')
            ->join('kelurahan', 'kelurahan.kelurahan_id', 'rw.kelurahan_id')
            ->when($isRw, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->when($isRt, function ($query) use ($rtID) {
                $query->where('rt.rt_id', $rtID);
            })
            ->orderBy('rt', 'ASC')
            ->get();
        $resultRT = '<option disabled selected></option>';
        foreach ($rt as $res) {
            if ($isRt) {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $rtID)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            } else {
                $resultRT .= '<option value="' . $res->rt_id . '"' . ((!empty($res->rt_id)) ? ((!empty($res->rt_id == $data->rt_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rt . '</option>';
            }
        }

        $rw = RW::select(
            'rw.rw',
            'rw.rw_id',
            'rw.kelurahan_id',
        )
            ->where('rw.kelurahan_id', $data->kelurahan_id)
            ->when($isRw || $isRt, function ($query) use ($rwID) {
                $query->where('rw.rw_id', $rwID);
            })
            ->orderBy('rw', 'ASC')
            ->get();
        $resultRW = '<option disabled selected></option>';
        foreach ($rw as $res) {
            if ($isRw || $isRt) {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $rwID)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            } else {
                $resultRW .= '<option value="' . $res->rw_id . '"' . ((!empty($res->rw_id)) ? ((!empty($res->rw_id == $data->rw_id)) ? ('selected') : ('')) : ('')) . '>' . $res->rw . '</option>';
            }
        }

        $kelurahan = Kelurahan::select(
            'kelurahan.nama',
            'kelurahan.kelurahan_id'
        )
            ->when($isRw || $isRt, function ($query) use ($kelurahanID) {
                $query->where('kelurahan.kelurahan_id', $kelurahanID);
            })
            ->orderBy('nama', 'ASC')
            ->get();
        $resultKelurahan = '<option disabled selected></option>';
        foreach ($kelurahan as $res) {
            if ($isRw || $isRt) {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $kelurahanID)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            } else {
                $resultKelurahan .= '<option value="' . $res->kelurahan_id . '"' . ((!empty($res->kelurahan_id)) ? ((!empty($res->kelurahan_id == $data->kelurahan_id)) ? ('selected') : ('')) : ('')) . '>' . $res->nama . '</option>';
            }
        }

        return view('Laporan.Laporan.edit', compact('data', 'result', 'resultAgenda', 'resultRT', 'resultRW', 'resultKelurahan'));
    }

    public function update(Request $request, $id)
    {
        $id = \Crypt::decryptString($id);

        $data = Laporan::findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['user_updated'] = \Auth::user()->user_id;
        $input['updated_at'] = date('Y-m-d H:i:s');

        \DB::beginTransaction();

        try {
            if ($request->hasFile('image')) {
                $input['image'] = 'report' . rand() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('upload/laporan/'), $input['image']);

                \File::delete(public_path('upload/laporan/' . $data->image));
            }
            if ($request->hasFile('upload_materi')) {
                $input['upload_materi'] = 'materi' . rand() . '.' . $request->upload_materi->getClientOriginalExtension();
                $request->upload_materi->move(public_path('upload/laporan/materi'), $input['upload_materi']);

                \File::delete(public_path('upload/laporan/materi/' . $data->upload_materi));
            }
            $data->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $data = Laporan::findOrFail($id);

        try {
            if ($data->image) {
                \File::delete(public_path('upload/laporan/' . $data->image));
            }
            Laporan::destroy($id);

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables(Request $request)
    {
        $checkRole = \helper::checkUserRole('all');
        $isRw = $checkRole['isRw'];
        $isRt = $checkRole['isRt'];

        $getData = $this->user->currentUser();
        $rwID = $getData->rw_id;
        $rtID = $getData->rt_id;

        $datatableButtons = method_exists(new Laporan, 'datatableButtons') ? Laporan::datatableButtons() : ['show', 'edit', 'destroy'];

        if (request()->ajax()) {
            $data = Laporan::select(
                'laporan.laporan_id',
                'laporan.laporan',
                'laporan.image',
                'laporan.created_at',
                'kat_laporan.nama_kategori as nama_kat'
            )
                ->join('kat_laporan', 'kat_laporan.kat_laporan_id', 'laporan.kat_laporan_id')
                ->when($isRw, function ($query) use ($rwID) {
                    $query->where('laporan.rw_id', $rwID);
                })
                ->when($isRt, function ($query) use ($rtID) {
                    $query->where('laporan.rt_id', $rtID);
                })
                ->when(!empty($request->kelurahan_id), function ($query) use ($request) {
                    $query->where('laporan.kelurahan_id', $request->kelurahan_id);
                    if (!empty($request->rw_id)) {
                        $query->where('laporan.rw_id', $request->rw_id);
                    }
                    if (!empty($request->rt_id)) {
                        $query->where('laporan.rt_id', $request->rt_id);
                    }
                })
                ->orderBy('laporan_id', 'desc')
                ->get();
        }

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                $image = '<img class="" src="'. \helper::loadImgUpload('laporan', $row->image) .'" alt="photo" style="width: 150px;max-width: 150px;height: 100px;object-fit: contain;" />';

                return $image;
            })
            ->addColumn('action', function ($data) use ($datatableButtons) {
                return view('partials.buttons.cust-datatable', [
                    'show2'         => ['name' => 'Detail', 'route' => route('Laporan.Laporan' . '.show', \Crypt::encryptString($data->laporan_id))],
                    'edit2'         => ['name' => 'Edit', 'route' => route('Laporan.Laporan' . '.edit', \Crypt::encryptString($data->laporan_id))],
                    'ajaxDestroy2'  => ['name' => 'Delete', 'id' => $data->laporan_id]
                ]);
            })
            ->editColumn('created_at', function($row){
                return \helper::tglIndo($row->created_at);
            })
            ->rawColumns(['action', 'image'])
            ->make('true');
    }

    public function getAlamat($id)
    {
        $laporanGetAlamat = \DB::table('laporan')->where('laporan_id', $id)->first();
        $laporan = [
            "subdistrict_id"   => "",
            "province_id"      => "",
            "province"         => "",
            "city_id"          => "",
            "city"             => "",
            "type"             => "",
            "subdistrict_name" => "",
            "laporan"        => ""
        ];

        if ($laporanGetAlamat) {
            if (empty($laporanGetAlamat->subdistrict_id)) {
                $laporan = json_decode($this->rajaOngkir->getSubdistrictDetailById(1579), true);
            } else {
                $laporan = json_decode($this->rajaOngkir->getSubdistrictDetailById($laporanGetAlamat->subdistrict_id), true);
            }
            $laporan['laporan'] = $laporanGetAlamat->laporan;
        }

        return $laporan;
    }
}
