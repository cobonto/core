<?php

namespace App\Http\Controllers\Admin;

use RmsCms\Controllers\AdminController;

class TestController extends AdminController
{
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
        ];
        return parent::index();
    }

    protected function edit($id)
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
                ],
                'submit' => [
                    [
                    'title' => 'Save',
                    'name' => 'save',
                    'type' => 'submit',
                    ],
                    [
                        'title' => 'Save and stay',
                        'name' => 'saveAndStay',
                        'type' => 'submit',
                    ],
                ]
            ],
        ];
        return parent::edit($id);
    }
}
