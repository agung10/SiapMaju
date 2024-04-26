<?php

namespace App\Models\RoleManagement;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'menu_id';
    protected $guarded    = [];

    public function datatableButtons(){
        return ['show', 'edit', 'destroy'];
    }
    
    // get menu parent collection
    public function parentMenu() {
    	return $this->where('menu_id', $this->id_parent)->first();
    }

    public function statusList() {
        return ['1' => 'Active', '0' => 'Inactive'];
    }
}
