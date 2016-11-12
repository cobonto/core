<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/5/2016
 * Time: 6:32 PM
 */

namespace Cobonto\Controllers\Admin;


use Cobonto\Controllers\AdminController;

class GroupsController extends AdminController
{
    protected function setProperties()
    {
        parent::setProperties();
        $this->route_name = 'groups';
        $this->model_name = 'Role';
        $this->table='roles';
        $this->prefix_model = 'Cobonto\Classes\Roles\\';
        $this->title = $this->lang('groups');
    }
    protected function fieldList()
    {
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
                        'title' => $this->lang('Name'),
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
        $this->request->merge(['admin'=>0]);
    }
    protected function listQuery()
    {
        parent::listQuery();
        return  $this->sql->where('admin',0);
    }
}