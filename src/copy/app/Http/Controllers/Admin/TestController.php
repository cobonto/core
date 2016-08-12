<?php

namespace App\Http\Controllers\Admin;

use Cobonto\Controllers\AdminController;

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
    }
}
