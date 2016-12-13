<?php

namespace Cobonto\Controllers\Admin;
use App\User;
use Cobonto\Classes\Roles\Role;
use Cobonto\Controllers\AdminController;


class UsersController extends AdminController
{

    protected $table = 'users';
    protected $route_name = 'users';
    protected $model_name = 'User';

    protected function setProperties()
    {
        $this->title = $this->lang('users');
        $this->prefix_model = 'Cobonto\\Classes\\';
        parent::setProperties();
    }

    protected function fieldList()
    {
        $this->fields_list = [
            'id' => [
                'title' => $this->lang('id'),
            ],
            'firstname' => [
                'title' => $this->lang('firstname'),
            ],
            'lastname' => [
                'title' => $this->lang('lastname'),
            ],
            'email' => [
                'title' => $this->lang('email'),
            ],
            'active' => [
                'filter_type'=>'bool',
                'title' => $this->lang('status'),
                'function' => 'displayStatus',
            ],
            'created_at' => [
                'filter_type'=>'date',
                'title' => $this->lang('created_at'),
            ]
        ];
    }

    protected function fieldForm()
    {
        $this->fields_form = [
            [
                'title' => 'Edit user',
                'input' => [
                    // text
                    [
                        'name' => 'firstname',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('firstname'),
                        //  'suffix' => '$',
                        //  'prefix' => '00.0',

                    ],
                    // text
                    [
                        'name' => 'lastname',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('lastname'),
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
                        'title' => $this->lang('password_confirmed'),

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
                    'title' => $this->l('group'),
                    'name' => 'role_id',
                    'col'=>'2',
                    'options' =>
                        [
                            'query' => Role::where(['admin' => 0])->get()->toArray(),
                            'key' => 'id',
                            'name' => 'name',
                        ],
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
                        'title' => $this->lang('save_and_stay'),
                        'type' => 'submit',
                        'class' => 'btn-warning'
                    ]

                ],

            ],
        ];
    }

    protected function beforeAdd()
    {
        $email = $this->request->input('email');
        if($email)
            if(User::getByEmail($email))
                $this->errors[] = $this->lang('email_used');
    }

    protected function beforeUpdate($id)
    {
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
}
