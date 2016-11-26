<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/5/2016
 * Time: 6:58 PM
 */

namespace Cobonto\Controllers\Admin;


use App\User;
use Cobonto\Classes\Roles\Role;
use Cobonto\Controllers\AdminController;

class EmployeesController extends AdminController
{

    protected function setProperties()
    {
         parent::setProperties();
         $this->title = $this->lang('employees');
         $this->table = 'users';
         $this->route_name = 'employees';
         $this->model_name = 'User';
    }

    protected function fieldList()
    {
        $this->skip_actions['destroy']=[1];
        $this->fields_list = [
            'id' => [
                'title' => $this->lang('id'),
            ],
            'name' => [
                'title' => $this->lang('name'),
            ],
            'email' => [
                'title' => $this->lang('email'),
            ],
            'active' => [
                'type'=>'bool',
                'title' => $this->lang('status'),
                'function' => 'displayStatus',
            ],
        ];
    }

    protected function fieldForm()
    {
        $this->fields_form = [
            [
                'title' => $this->lang('edit_employee'),
                'input' => [
                    // text
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('name'),
                        //  'suffix' => '$',
                        //  'prefix' => '00.0',

                    ],
                    // text
                    [
                        'name' => 'email',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('email'),
                        //  'suffix' => '$',
                        //  'prefix' => '00.0',

                    ],
                    [
                        'name' => 'password',
                        'type' => 'password',
                        'class' => '',
                        'col' => '3',
                        'title' => $this->lang('password'),

                    ],
                    [
                        'name' => 'password_confirmation',
                        'type' => 'password',
                        'class' => '',
                        'col' => '3',
                        'title' => $this->lang('passwd_confirm'),

                    ],
                    // date picker
                    [
                        'name' => 'active',
                        'type' => 'switch',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('status'),
                        'default_value' => 1,
                        'jqueryOptions' => [
                            'onColor' => 'success',
                            'offColor' => 'danger',
                            'onText' => $this->lang('yes'),
                            'offText' => $this->lang('no'),
                        ]
                    ],
                      [
                              'type' => 'select',
                              'title' => $this->l('role'),
                              'name' => 'role_id',
                              'options' =>
                               [
                                'query' => Role::where(['admin'=>1])->get()->toArray(),
                                'key' => 'id',
                                'name' => 'name',
                              ],
                              'required' => false,
                            ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => $this->lang('save'),
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
        $this->request->merge(['is_admin'=>1]);
        $email = $this->request->input('email');
        if($email)
            if(User::getByEmail($email))
                $this->errors[] = $this->lang('email_used');
    }

    protected function beforeUpdate($id)
    {
        $this->request->merge(['is_admin'=>1]);
        $email = $this->request->input('email');
        if($email)
        {
            $new_id = User::getByEmail($email,true);
            if($new_id)
            {
                if($new_id !=$id)
                    $this->errors[] = $this->lang('email_used');
            }
        }

    }
    protected function listQuery()
    {
        parent::listQuery();
        return  $this->sql->where('is_admin',1);
    }
}