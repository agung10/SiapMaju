<?php

namespace App\Traits;
use App\Traits\StorageTrait;
use \Illuminate\Support\Facades\Schema;

trait EloquentTrait{
    
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    // Get relations of model for the determined record
    public function withShow($relations,$id)
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    // create a new record in the database
    public function store(array $data, $storeImage=false, $recordCreated=false)
    {
        // remove proengsoft_jsvalidation from array input if exist
        unset($data['proengsoft_jsvalidation']);

        \DB::beginTransaction();
        $transaction = false;

        try {
            
            $files = [];

            if($storeImage){
                foreach ($data as $key => $value) {
                    if(is_object($value)){
                        $data[$key]   = 'TEMP_FILE';

                        $file['name'] = $key;
                        $file['file'] = $value;

                        array_push($files, $file);
                    }
                }   
            }

            $data        = $this->storeExtraData($data);
            $storeRecord = $this->model->create($data);
            if( !empty($files) ) $this->upload($files, $storeRecord);            

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();

            // error page
            throw $e;
        }
        
        // return $recordCreated ? $storeRecord : $transaction;
        return response()->json(['status' => 'success']);
    }

    // update record in the database
    public function update(array $data, $id, $updateImage=false, $recordUpdated=false)
    {
        // remove proengsoft_jsvalidation from array input if exist
        unset($data['proengsoft_jsvalidation']);
        
        \DB::beginTransaction();
        $trans = false;

        try {

            $record = $this->show($id);
            $files  = [];

            if($updateImage){
                foreach ($data as $key => $value) {
                    if(is_object($value)){
                        // unset($data[$key]);
                        $data[$key]   = 'TEMP_FILE';

                        $file['name']     = $key;
                        $file['file']     = $value;
                        $file['old_file'] = $record->$key;

                        array_push($files, $file);
                    }
                }
            }

            $data         = $this->updateExtraData($data);
            $updateRecord = $record->update($data);
            if( !empty($files) ) $this->upload($files, $record);

            \DB::commit();
            $trans = true;
        } catch (\Exception $e) {
            \DB::rollback();
            
            // error page
            throw $e;
        }
        
        // return $recordUpdated ? $record : $trans;
        return response()->json(['status' => 'success']);
    }

    // delete old record using batch array
    public function deleteBatch(string $field, array $ids)
    {
        \DB::beginTransaction();
        $transaction = false;

        try {
            $this->model->whereIn($field, $ids)->delete();

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();

            // error page
            throw $e;
        }
        
        return $transaction;
    }

    // insert new record using batch array
    public function storeBatch(array $data)
    {
        \DB::beginTransaction();
        $transaction = false;

        try {
            $this->model->insert($data);

            \DB::commit();
            $transaction = true;
        } catch (\Exception $e) {
            \DB::rollback();

            // error page
            throw $e;
        }
        
        return $transaction;
    }

    public function storeExtraData(array $data)
    {
        if( Schema::hasColumn($this->model->getTable(), 'created_by') && !array_key_exists('created_by', $data) )
        {
            $data['created_by'] = \Auth::user()->user_id;
        }

        return $data;
    }
    
    public function updateExtraData(array $data)
    {
        if( Schema::hasColumn($this->model->getTable(), 'updated_by') && !array_key_exists('updated_by', $data) )
        {
            $data['updated_by'] = \Auth::user()->user_id;
        }

        return $data;
    }

    // remove record from the database
    public function delete($id)
    {        
        return $this->model->destroy($id);
    }

    public function makeDropdown($indexField)
    {
        return $this->model->orderBy($indexField)->pluck($indexField, $this->model->getKeyName());
    }
}