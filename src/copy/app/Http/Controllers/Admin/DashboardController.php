<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests;
use Module\Classes\Hook;
use Cobonto\Controllers\AdminController;

class DashboardController extends AdminController
{
    //
    protected $tpl ='dashboard';
    public function index()
    {
        // add 3 hook for dashboard
        $this->assign->params([
            // hooks
           'HOOK_DASHBOARD_TOP'=>Hook::execute('displayDashBoardTop'),
            'HOOK_DASHBOARD_RIGHT'=>Hook::execute('displayDashBoardRight'),
            'HOOK_DASHBOARD_LEFT'=>Hook::execute('displayDashBoardLeft'),
            'HOOK_DASHBOARD_FOOTER'=>Hook::execute('displayDashBoardFooter'),
        ]);
        $this->title = trans('admin.Dashboard');
      return parent::view();
    }
}
