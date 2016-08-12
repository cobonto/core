<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Support\Facades\Input;
use Cobonto\Controllers\AdminController;


class UserController extends AdminController
{
    protected $title = 'Users';
    protected $table = 'users';
    protected $route_name = 'users';
    protected $model_name = 'User';

    protected function index()
    {
        $this->fields_list = [
            'id' => [
                'title' => 'ID',
            ],
            'name' => [
                'title' => 'Name',
            ],
            'email' => [
                'title' => 'Email',
            ],
            'active' => [
                'title' => 'Status',
                'function' => 'displayStatus',
            ],
            'created_at' => [
                'title' => 'Created at',
            ]
        ];
        return parent::index();
    }

    protected function fieldForm()
    {
        $this->fields_form = [
            [
                'title' => 'Edit user',
                'input' => [
                    // text
                    [
                        'name' => 'name',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => 'Name',
                        //  'suffix' => '$',
                        //  'prefix' => '00.0',

                    ],
                    // text
                    [
                        'name' => 'email',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => 'Email',
                        //  'suffix' => '$',
                        //  'prefix' => '00.0',

                    ],
                    [
                        'name' => 'password',
                        'type' => 'password',
                        'class' => '',
                        'col' => '3',
                        'title' => 'Password',

                    ],
                    [
                        'name' => 'password_confirmation',
                        'type' => 'password',
                        'class' => '',
                        'col' => '3',
                        'title' => 'Password confirm',

                    ],
                    // date picker
                    [
                        'name' => 'active',
                        'type' => 'switch',
                        'class' => '',
                        'col' => '6',
                        'title' => 'Status',
                        'default_value' => 1,
                        'jqueryOptions' => [
                            'onColor' => 'success',
                            'offColor' => 'danger',
                            'onText' => 'Yes',
                            'offText' => 'No',
                        ]
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => 'Save',
                        'type' => 'submit',
                    ],
                    [
                        'name' => 'saveAndStay',
                        'title' => 'Save and stay',
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
                $this->errors[] = 'This email is already is exists';
    }

    protected function beforeUpdate($id)
    {
        $email = $this->request->input('email');
        if($email)
        {
            $new_id = User::getByEmail($email,true);
            {
                if($new_id !=$id)
                    $this->errors[] = 'This email is already used';
            }
        }


    }
}
