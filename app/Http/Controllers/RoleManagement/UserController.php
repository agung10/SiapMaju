<?php

namespace App\Http\Controllers\RoleManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RoleManagement\{ UserRepository, RoleRepository, UserRoleRepository };
use App\Http\Requests\RoleManagement\UserRequest;
use App\Helpers\helper;
use App\Models\Master\Keluarga\AnggotaKeluarga;

class UserController extends Controller
{
    public function __construct(UserRepository $user, RoleRepository $role, UserRoleRepository $userRole)
    {
        $this->user      = $user;
        $this->role      = $role;
        $this->userRole  = $userRole;
    }

    public function index()
    {
        return view ('Master.role_management.user.index');
    }

    public function create()
    {
        $roles = $this->role->model->get();

        return view ('Master.role_management.user.create', compact('roles') );
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => ['required', 'max:150', 'unique:users,username'],
            'email'    => ['required','email', 'max:150', 'unique:users,email'],
            'role_id'  => ['required'],
            'password' => ['required','string','min:6','confirmed'],
            'picture'  => ['nullable', 'image', 'max:2000']
        ];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }
        $namafile = $request->picture;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $namafile = time().'.'.$file->getClientOriginalExtension();
            $file->move('uploaded_files/users', $namafile);
        }

        $new                     = new $this->user->model;
        $new->username           = $request->username;
        $new->email              = strtolower($request->email);
        $new->password           = bcrypt($request->password);
        $new->picture            = $namafile;
        $new->email_verified_at  = date('Y-m-d H:i:s');
        $new->save();

        $newrole           = new $this->userRole->model;
        $newrole->user_id  = $new->user_id;
        $newrole->role_id  = $request->role_id;
        $newrole->save();

        return response()->json(['status' => 'success']);
    }

    public function show($userId)
    {   
        $userId = \Crypt::decryptString($userId);
        $roles = $this->role->model->get();
        $data  = $this->user->withShow('role', $userId);

        return view('Master.role_management.user.show', compact('data', 'roles'));
    }

    public function edit($userId)
    {   
        $userId = \Crypt::decryptString($userId);

        $data         = $this->user->withShow('role', $userId);
        $roles        = $this->role->model->get();
        $user_role_id = $this->userRole->model->where('user_id', $data->user_id)->value('user_role_id');

        return view('Master.role_management.user.edit', compact('data', 'roles', 'user_role_id'));
    }

    public function update(Request $request, $userId)
    {   
        $userId = \Crypt::decryptString($userId);
        
        $rules = [
            'username' => ['required', 'max:150', 'unique:users,username,'. $userId .',user_id'],
            'email'    => ['required','email', 'max:150', 'unique:users,email,'. $userId .',user_id'],
            'role_id'  => ['required'],
            'password' => ['nullable','string','min:6','confirmed'],
            'picture'  => ['nullable', 'image', 'max:2000']
        ];

        $validator = helper::validation($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['status' => 'failed', 'errors' => $validator->getMessageBag()->toArray()]);
        }

        $namafile  = $request->picture;
        $upd       = $this->user->show($userId);

        if ($request->hasFile('picture')) {
            $file      = $request->file('picture');
            $namafile  = time().'.'.$file->getClientOriginalExtension();
            $oldFile   = public_path().'/uploaded_files/users/'.$upd->picture;

            if (file_exists($oldFile)) {
                \File::delete($oldFile);
            }
            $file->move('uploaded_files/users', $namafile);
        }
        $upd->username           = $request->username;
        $upd->email              = strtolower($request->email);
        if($request->password){
            $upd->password       = bcrypt($request->password);
        }
        if($request->hasFile('picture')){
            $upd->picture            = $namafile;
        }
        $upd->email_verified_at  = date('Y-m-d H:i:s');
        $upd->save();

        $updrole           = $this->userRole->show($request->user_role_id);
        $updrole->role_id  = $request->role_id;
        $updrole->save();

        return response()->json(['status' => 'success']);
    }

    public function updateProfilPicture(Request $request,$id)
    {
        $response = false;
        $input = $request->all();
        $upd       = $this->user->show($id);

        $namafile  = $request->picture;
        
        if ($request->hasFile('picture')) {
            $file      = $request->file('picture');
            $namafile  = time().'.'.$file->getClientOriginalExtension();
            $oldFile   = public_path().'/uploaded_files/users/'.$upd->picture;

            if (file_exists($oldFile)) {
                \File::delete($oldFile);
            }
            $file->move('uploaded_files/users', $namafile);
            $upd->picture = $namafile;
            $upd->save();

            $response = true;
        }
                
        return response()->json(['status' => $response]);
    }

    public function destroy($id)
    {
        $user = $this->user->select('anggota_keluarga_id')->findOrFail($id);

        \DB::beginTransaction();
        try {
            AnggotaKeluarga::where('anggota_keluarga_id', $user->anggota_keluarga_id)->delete();
            $this->user->destroy($id);

            \DB::commit();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }
    }
    

    public function dataTables(Request $request)
    {
        $isAdmin = \helper::checkUserRole('admin');

        $datatableButtons = method_exists(new $this->user->model, 'datatableButtons') ? $this->user->model->datatableButtons() : ['show', 'edit', 'destroy'];

        $data = $this->user->model->select('users.user_id', 'users.username', 'users.email','anggota_keluarga.nama', 'blok.nama_blok')
                            ->leftJoin('anggota_keluarga','anggota_keluarga.anggota_keluarga_id','users.anggota_keluarga_id')
                            ->leftJoin('keluarga','keluarga.keluarga_id','anggota_keluarga.keluarga_id')
                            ->leftJoin('blok','blok.blok_id','keluarga.blok_id')
                            ->when($isAdmin != true, function ($query){
                                $query->where('anggota_keluarga.is_active', '!=', false);
                            })
                            ->with('role');

        return \DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('cust_role', function($data){
            return $data->role->role_name ?? '-';
        })
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('master.user'.'.show', \Crypt::encryptString($data->user_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('master.user'.'.edit', \Crypt::encryptString($data->user_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->user_id : null
            ]);
        })
        ->addColumn('nama',function($data){
            return $data->nama ?? $data->username;
        })
        ->rawColumns(['action', 'cust_role'])
        ->make(true);
    }
}
