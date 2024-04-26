<?php

namespace App\Repositories;

use App\Models\Gallery\GalleryContent;
use App\Helpers\helper;

class GalleryContentRepository
{
    public function __construct(GalleryContent $_GalleryContent)
    {
        $this->content = $_GalleryContent;
    }

    public function store($request)
    {
        \DB::beginTransaction();

        try{
            if ($request->file && $request->keterangan) {
                foreach ($request->file as $key => $val) {
                    
                    $imageName = 'content'.rand().'.'.$val->getClientOriginalExtension();
                    $val->move(public_path('/upload/galeri/konten'), $imageName);
                    $file[] = $imageName;
                    
                    if ($val && $request->keterangan[$key]) {
                        $data = [
                            'galeri_id' => $request->galeri_id,
                            'agenda_id' => $request->agenda_id,
                            'keterangan' => $request->keterangan[$key],
                            'kategori_file' => $request->kategori_file,
                            'file' => $imageName,
                        ];
                        $this->content->create($data);
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
        $model = $this->content->findOrFail($id);

        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;

        \DB::beginTransaction();

        try{
            if($request->hasFile('file')){
                $input['file'] = 'content'.rand().'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('upload/galeri/konten'),$input['file']);

                \File::delete(public_path('upload/galeri/konten/'.$model->file));
            }

            $model->update($input);

            \DB::commit();

            return response()->json(['status' => 'success']);


        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    public function dataTables($galeri_id)
    {
        $datatableButtons = method_exists(new $this->content, 'datatableButtons') ? $this->content->datatableButtons() : ['show', 'edit', 'destroy'];
    	$model = \DB::table('galeri_konten')
                    ->select('galeri_konten.galeri_konten_id','galeri_konten.file','galeri_konten.keterangan','galeri_konten.updated_at')
                    ->where('galeri_konten.galeri_id',$galeri_id);
                    
        return \DataTables::of($model)
        ->addIndexColumn()
        ->editColumn('file',function($row){
            return view('layouts.partials.image_column', ['src' => \helper::loadImgUpload('galeri/konten', $row->file)]);
        })
        ->addColumn('action', function($data) use ($datatableButtons, $galeri_id) {
                                return view('partials.buttons.cust-datatable',[
                                    'show'         => in_array("show", $datatableButtons ) ? route('Gallery.Content'.'.show', ["galeri_id" => $galeri_id, "GalleryContent" => \Crypt::encryptString($data->galeri_konten_id)]) : null,
                                    'edit'         => in_array("edit", $datatableButtons ) ? route('Gallery.Content'.'.edit', ["galeri_id" => $galeri_id, "GalleryContent" => \Crypt::encryptString($data->galeri_konten_id)]) : null,
                                    'ajax_destroy' => in_array("destroy", $datatableButtons ) ? $data->galeri_konten_id : null
                                ]);
                            })
        ->rawColumns(['file','action'])
        ->make(true);
    }

    public function delete($id)
    {
        $model = $this->content->findOrFail($id);
        try{

            if($model->file){
                \File::delete(public_path('upload/galeri/'.$model->file));
            }

            $this->content->destroy($id);

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

}