<?php

namespace App\Repositories\RoleManagement;

use App\Models\RoleManagement\Menu;
use App\Repositories\BaseRepository;
use App\Traits\EloquentTrait;


class MenuRepository
{
    use EloquentTrait;
    
    public function __construct(Menu $menu, RoleRepository $role)
    {
        $this->model = $menu;
    }
    
    public function menuList() {
    	$menuStructure = collect([ 0 => '• root menu •']);

    	$parents = $this->model->whereNull('id_parent')->orderBy('order')->get();
        
    	foreach ($parents as $parent) {
    		$menuStructure->put($parent->menu_id, $parent->name);

    		$childs = $this->model->where('id_parent', $parent->menu_id)->orderBy('order')->get();

    		foreach ($childs as $child) {
    			$menuStructure->put($child->menu_id, '&emsp;'. $child->name);
    		}
    	}

    	return $menuStructure;
    }

    public function storeMenu($request) {
        $request['id_parent'] = $request['id_parent'] === "0" ? null : $request['id_parent'];
        $increaseMenu = $this->reorderMenu($request, true);

        return $this->store($request);
    }

    public function updateMenu($request, $menuId) {
        $request['id_parent'] = $request['id_parent'] === "0" ? null : $request['id_parent'];
        $increaseMenu = $this->reorderMenu($request, true);

        return $this->update($request, $menuId);
    }

    public function deleteMenu($menuId) {
        $menu = $this->show($menuId)->toArray();
        $decreaseMenu = $this->reorderMenu($menu, false);

        return $this->delete($menuId);
    }

    /* when increase true then add order higher menu by 1
    *  when increase false then reduce order higher menu by 1
    */
    public function reorderMenu($request, $increase) {
        $higherOrder  = $this->model
                            ->where('id_parent', $request['id_parent'])
                            ->where('order', $request['order'])
                            ->first();
        $higherOrders = [];
        
        if($higherOrder){
            $higherOrders = $this->model
                                 ->where('id_parent', $request['id_parent'])
                                 ->where('order', '>=', $request['order'])
                                 ->get();
        }

        foreach ($higherOrders as $menu) {
            $newOrder = $increase ? $menu->order + 1 : $menu->order - 1;
            $menu->update(['order' => $newOrder]);
        }

        return true;
    }

}