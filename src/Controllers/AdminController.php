<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/5/2016
 * Time: 6:58 PM
 */

namespace Cobonto\Controllers\Admin;


use App\User;
use Cobonto\Classes\Admin;
use Cobonto\Classes\Roles\Role;
use Cobonto\Classes\Translate;
use Cobonto\Controllers\AdminController;
use Intervention\Image\Image;

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
                        'col' => 2,
                        'options' =>
                            [
                                'query' => $data,
                                'key' => 'lang',
                                'name' => 'name',
                            ],
                        'required' => false,
                    ],
                    [
                        'type' => 'file',
                        'title' => 'عکس پروفایل',
                        'name'=>'file',
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
        $roles = Role::where(['admin' => 1]);
        if ($this->admin->role_id != 1)
        {
            $roles->where('id', '=', $this->admin->role_id);
        }
        elseif ($this->admin->role_id == 1 && $this->request->route()->parameter('admins') == 1)
            $roles->where('id', '=', $this->admin->role_id);
        $id = false;
        if (!$id || ($id && $id != $this->admin->id) || ($id != 1))
            $this->fields_form[0]['input'][] = [
                'type' => 'select',
                'title' => $this->l('role'),
                'name' => 'role_id',
                'col' => '2',
                'options' =>
                    [
                        'query' => $roles->get()->toArray(),
                        'key' => 'id',
                        'name' => 'name',
                    ],
                'required' => false,
            ];
    }

    public function edit($id)
    {
        $access = $this->access();
        if ($access !== true)
            return $access;
        return parent::edit($id); // TODO: Change the autogenerated stub
    }

    protected function beforeAdd()
    {
        $email = $this->request->input('email');
        if ($email)
            if (Admin::getByEmail($email))
                $this->errors[] = $this->lang('email_used');
    }
    protected function afterAdd($id)
    {
        $this->uploadImage($id);
        parent::afterAdd($id); // TODO: Change the autogenerated stub
    }
    protected function afterUpdate($id)
    {
        $this->uploadImage($id);
        parent::afterAdd($id); // TODO: Change the autogenerated stub
    }
    protected function beforeUpdate($id)
    {
        if ($this->admin->role_id != 1)
        {
            $role_id = $this->admin->role_id;
        }
        elseif ($this->admin->role_id == 1 && $this->request->route()->parameter('admins') == 1)
            $role_id = 1;
        else
            $role_id = $this->request->input('role_id');
        $this->model->forceEntityHydrationFromInput = true;
        if (!$this->request->password)
            $this->request->merge([
                'password' => $this->model->password,
                'role_id' => $role_id,
            ]);
        $email = $this->request->input('email');
        if ($email)
        {
            $new_id = Admin::getByEmail($email, true);
            if ($new_id)
            {
                if ($new_id != $id)
                    $this->errors[] = $this->lang('email_used');
            }
        }

    }

    protected function access()
    {
        // add restricted access
        if ($this->admin->role_id == 1)
            return true;
        else
        {
            $id = $this->request->route()->parameter('admins');
            if ($id && $this->admin->id != $id)
            {
                return redirect(adminRoute('403'));
            }
            else
            {
                return true;
            }
        }
    }

    protected function uploadImage($id)
    {
        if($this->request->file('file')){
            \Image::make($this->request->file('file'))->resize(40,40)->save(public_path('img/admin/'.$id.'.png'));
        }
    }
}