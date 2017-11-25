<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/5/2016
 * Time: 6:32 PM
 */

namespace Cobonto\Controllers\Admin;


use Cobonto\Controllers\AdminController;

class RolesController extends AdminController
{
    protected $filter = false;
    protected function setProperties()
    {
        parent::setProperties();
        $this->model_name = 'Role';
        $this->table = 'roles';
        $this->prefix_model = 'Cobonto\Classes\Roles\\';
        $this->title = $this->lang('roles');

    }
    protected function fieldList()
    {
        $this->skip_actions['destroy']=[1];
        $this->fields_list = [
            'id' => [
                'title' => $this->lang('id'),
            ],
            'name' => [
                'title' =>  $this->lang('name'),
            ],
        ];
    }

    protected function fieldForm()
    {
        $this->fields_form = [
            [
                'title' => $this->lang('edit_roles'),
                'input' => [
                    // text
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('name'),
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' =>$this->lang('save'),
                        'type' => 'submit',
                    ],
                    [
                        'name' => 'saveAndStay',
                        'title' => $this->lang('save_stay'),
                        'type' => 'submit',
                        'class' => 'btn-info'
                    ]

                ],

            ],
        ];
    }

    protected function beforeAdd()
    {
        $this->request->merge(['admin'=>1]);
    }
    protected function listQuery()
    {
        return  parent::listQuery()->where('admin',1);
    }
}