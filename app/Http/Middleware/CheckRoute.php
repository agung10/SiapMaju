<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\{
    MenuPermissionTrait,
    PermissionTrait
};

class CheckRoute
{
    use MenuPermissionTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // don't check permission on ajax request or user not logged in or menu dashboard
        if ($request->ajax() || !\Auth::user() || $this->currentRoute('route') === 'home' || $this->currentRoute('route') === 'landing' || strpos($request->url(), "get") !== false || $this->currentRoute('route') == 'Surat.SuratPermohonan' || $this->currentRoute('route') == 'LaporanSikad.LaporanTransaksiKegiatan' || $this->currentRoute('route') == 'LaporanSikad.LaporanTransaksiKegiatanDKM' || $this->currentRoute('route') == 'Surat.checkSuratPermohonan' || $this->currentRoute('route') == 'Kelurahan.Surat' || $this->currentRoute('route') == 'LaporanSikad.LaporanTransaksiIdulFitri') {
            return $next($request);
        }

        // bypass permission if function name contains: create_ OR store_  OR show_ OR edit_ OR update_ OR destroy_
        if (isset($this->currentPermissionDetail()['name'])) {
            $permission = $this->currentPermissionDetail()['name'];

            if (strpos($permission, 'create_') !== false || strpos($permission, 'store_') !== false || strpos($permission, 'show_') !== false || strpos($permission, 'edit_') !== false || strpos($permission, 'update_') !== false || strpos($permission, 'destroy_') !== false) {
                return $next($request);
            }
        }

        // get available permission on logged in user
        $permissions = $this->availablePermission();
        // get available menu access on logged in user
        $menuAccess  = \Auth::user()->role->menu;

        // loop menu access to check if accessed route/url available in menu
        foreach ($menuAccess as $key => $value) {

            if ($value->route === $this->currentRoute('route')) {

                if (in_array($this->currentRoute('action'), $permissions)) {
                    return $next($request);
                }
            }
        }

        // return 403 page if user have no access to route or permissions
        abort(403);
    }
}
