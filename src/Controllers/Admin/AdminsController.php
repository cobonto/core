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
use Cobonto\Classes\Translate;
use Cobonto\Controllers\AdminController;

class AdminsController extends AdminController
{

    protected function setProperties()
    {
        $this->route_name = 'admins';
        $this->model_name = 'Admin';
        $this->table = 'admins';
        $this->prefix_model = 'Cobonto\\Classes\\';
        parent::setProperties();
        $this->title = $this->lang('admins');


    }

    protected function fieldList()
    {
        $this->skip_actions['destroy'] = [1];
        $this->fields_list = [
            'id' => [
                'title' => $this->lang('id'),
            ],
            'firstname' => [
                'title' => $this->lang('name'),
            ],
            'lastname' => [
                'title' => $this->lang('name'),
            ],
            'email' => [
                'title' => $this->lang('email'),
            ],
            'active' => [
                'type' => 'bool',
                'title' => $this->lang('status'),
                'function' => 'displayStatus',
            ],
        ];
    }

    protected function fieldForm()
    {
        $languages = Translate::getLanguages();
        foreach ($languages as $language)
            $data[] = ['lang' => basename($language), 'name' => basename($language)];
        $this->fields_form = [
            [
                'title' => $this->lang('edit_admin'),
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
                    'title' => $this->l('language'),
                    'name' => 'lang',
                    'col'=>2,
                    'options' =>
                        [
                            'query' => $data,
                            'key' => 'lang',
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
        // add role
        $id = $this->request->route()->parameter('admins');
        if (!$id || ($id && $id != $this->admin->id) || ($id !=1))
            $this->fields_form[0]['input'][] = [
                'type' => 'select',
                'title' => $this->l('role'),
                'name' => 'role_id',
                'col'=>'2',
                'options' =>
                    [
                        'query' => Role::where(['admin' => 1])->get()->toArray(),
                        'key' => 'id',
                        'name' => 'name',
                    ],
                'required' => false,
            ];
    }

    protected function beforeAdd()
    {
        $email = $this->request->input('email');
        if ($email)
            if (User::getByEmail($email))
                $this->errors[] = $this->lang('email_used');
    }

    protected function beforeUpdate($id)
    {
        $email = $this->request->input('email');
        if ($email)
        {
            $new_id = User::getByEmail($email, true);
            if ($new_id)
            {
                if ($new_id != $id)
                    $this->errors[] = $this->lang('email_used');
            }
        }

    }
}