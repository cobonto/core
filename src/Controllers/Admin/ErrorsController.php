<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/10/2016
 * Time: 1:36 PM
 */

namespace Cobonto\Controllers\Admin;


use Cobonto\Controllers\AdminController;

class ErrorsController extends AdminController
{
    protected function limitedAccess()
    {
        $this->tpl='errors.403';
        return parent::view();
    }
    protected function notFound()
    {
        $this->tpl='errors.404';
        return parent::view();
    }
}