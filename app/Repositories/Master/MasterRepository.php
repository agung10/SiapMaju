<?php
namespace App\Repositories\Master;

use App\Helpers\helper;

class MasterRepository
{
	
 	public function store($request, $model)
 	{ 
 		$input = $request->except('proengsoft_jsvalidation');
     		if ($request->password) {
                $input['password'] = bcrypt($request->password);
            }
        \DB::beginTransaction();

        try {
            $model->create($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
 	}

 	public function update($id, $request, $rules, $model)
 	{
        $data = $model->findOrFail($id);
        $input = $request->except('proengsoft_jsvalidation');
        $input['updated_at'] = date('Y-m-d H:i:s');
        $input['user_updated'] = \Auth::user()->user_id;
        \DB::beginTransaction();

        try {
            $data->update($input);
            \DB::commit();

            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            \DB::rollback();
            throw $e;
        }
 	}

 	public function destroy($id, $model)
 	{
        try{
            $model->destroy($id);

            return response()->json(['status' => 'success']);

        }catch(\Exception $e){
            \DB::rollback();
            throw $e;
        }
 	}
}
