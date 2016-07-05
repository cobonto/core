<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use RmsCms\Controllers\AdminController;
use Yajra\Datatables\Datatables;

class UserController extends AdminController
{
    protected $tpl ='user';
    protected function getIndex()
    {
        return parent::view();
    }
    public function myData()
    {
        return Datatables::of(User::query())->make(true);
    }
    protected function setMedia()
    {
        parent::setMedia();
        app('assign')->addCSS('plugins/datatables/dataTables.bootstrap.css');
        app('assign')->addJS([
            'plugins/datatables/jquery.dataTables.min.js',
            'plugins/datatables/dataTables.bootstrap.min.js'

        ]);
    }

}
