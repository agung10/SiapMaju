<?php

namespace App\Repositories;

use App\Models\Gallery\Gallery;
use App\Helpers\helper;

class GalleryRepository
{
    public function __construct(Gallery $_Gallery)
    {
        $this->gallery = $_Gallery;
    }

    public function store($request)
    {
        \DB::beginTransaction();

        try{

            if ($request->image_cover && $request->detail_galeri) {
                foreach ($request->image_cover as $key => $val) {
                    
                    $imageName = 'galeri'.rand().'.'.$val->getClientOriginalExtension();
                    $val->move(public_path('/upload/galeri'), $imageName);
                    $image_cover[] = $imageName;

                    if ($val && $request->detail_galeri[$key]) {
                        $data = [
                            'agenda_id' => $request->agenda_id,
                            'image_cover' => $imageName,
                            'detail_galeri' => $request->detail_galeri[$key]
                        ];

                        $this->gallery->create($data);
                    }
                }
            }

            \DB::commit();
            return response()->json(['status' => 'success']);
        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        $model = $this->gallery->findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try{
            if($request->hasFile('image_cover')){
                $input['image_cover'] = 'galeri'.rand().'.'.$request->image_cover->getClientOriginalExtension();
                $request->image_cover->move(public_path('upload/galeri'),$input['image_cover']);

                \File::delete(public_path('upload/galeri/'.$model->image_cover));
            }

            $model->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);


        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables()
    {
        $datatableButtons = method_exists(new $this->gallery, 'datatableButtons') ? $this->gallery->datatableButtons() : ['show', 'edit', 'destroy'];
    	$model = \DB::table('galeri')
                    ->select('galeri.galeri_id','galeri.detail_galeri','galeri.image_cover','galeri.created_at');
        return \DataTables::of($model)
        ->addIndexColumn()
        ->editColumn('image_cover',function($row){
            return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('galeri', $row->image_cover)]);
        })
        ->addColumn('action', function($data) use ($datatableButtons) {
            return view('partials.buttons.cust-datatable',[
                'show'         => in_array("show", $datatableButtons ) ? route('Gallery.List'.'.show', \Crypt::encryptString($data->galeri_id)) : null,
                'edit'         => in_array("edit", $datatableButtons ) ? route('Gallery.List'.'.edit', \Crypt::encryptString($data->galeri_id)) : null,
                'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->galeri_id : null,

                'customButton' => ['route' => route('Gallery.Content.index', $data->galeri_id), 'name' => 'Tambah Galeri Konten']
            ]);
        })
        ->rawColumns(['image_cover','action'])
        ->make(true);
    }

    public function delete($id)
    {
        $model = $this->gallery->findOrFail($id);
        
        try{

            if($model->image_cover){
                \File::delete(public_path('upload/galeri/'.$model->image_cover));
            }

            $this->gallery->destroy($id);

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

}