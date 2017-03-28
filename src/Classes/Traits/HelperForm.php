<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/16/2016
 * Time: 7:32 PM
 */

namespace Cobonto\Classes\Traits;


trait HelperForm
{
    /** @var  array $fields form */
    protected $fields_form = [];
    /** @var  array $fields form */
    protected $fields_values = [];
    /** @var array $tpl_form */
    protected $tpl_form = 'admin.helpers.form.main';
    /** @var array available plugin for form */
    private $available_plugins = [
        'selecttwo',
        'colorpicker',
        'datepicker',
        'ckeditor',
        'tree',
    ];
    /** @var array list of switchers */
    protected $switchers = ['active'];

    /**
     * create form with helper form
     */
    protected function generateForm()
    {
        // check fields_value for edit it
        $dataFormDb = true;
        if (count($this->fields_values))
            $dataFormDb = false;
        if (count($this->fields_form))
        {
            // add plugins
            foreach ($this->fields_form as &$forms)
            {
                if (isset($forms['input']))
                    foreach ($forms['input'] as &$field)
                    {
                        // add field values
                        if ($dataFormDb)
                        {
                            if (is_object($this->model) && $this->model->id)
                            {
                                $this->fields_values[$field['name']] = $this->model->{$field['name']};
                            }
                            else
                            {
                                $this->fields_values[$field['name']] = (isset($field['default_value']) ? $field['default_value'] : null);
                            }
                        }

                        if (in_array($field['type'], $this->available_plugins))
                            $this->assign->addPlugin($field['type']);
                        if ($field['type'] == 'selecttwo')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").select2({';
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'inputmask')
                        {
                            $this->assign->addJS('plugins/inputmask/jquery.inputmask.js', true);
                            // check has extenstions
                            if (isset($field['extensions']))
                            {
                                $this->assign->addJS([
                                    'plugins/inputmask/jquery.inputmask.' . $field['extensions'] . '.extensions.js',
                                    'plugins/inputmask/jquery.inputmask.extensions.js',
                                ], true);
                            }

                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").inputmask()';

                        }
                        elseif ($field['type'] == 'colorpicker')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").colorpicker({';
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'datepicker')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").datepicker({';
                            // add options
                            $this->addJqueryOptions($field);

                        }
                        elseif ($field['type'] == 'tree')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").treeview({
                        data:' . $field['data'] . ',';
                            // add options
                            $this->assign->addJSVars([
                                'tree_id' => $id,
                                'multiSelect' => $field['multiSelect'],
                                'tree_name' => $field['name'],
                            ]);
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'textarea' && isset($field['class']) && $field['class'] == 'ckeditor')
                        {
                            $contentsLangDirection = 'ltr';
                            $cke_lang = 'en';
                            $this->assign->addJS('plugins/ckeditor/ckeditor.js', true);
                            if (config('app.rtl'))
                            {
                                $contentsLangDirection = 'rtl';
                                $cke_lang = 'fa';
                            }
                            $this->assign->addJSVars(
                                [
                                    'eldifnder_url' => route('elfinder.ckeditor'),
                                    'contentsLangDirection' => $contentsLangDirection,
                                    'cke_lang' => $cke_lang,
                                ]
                            );

                        }
                        elseif ($field['type'] == 'switch')
                        {
                            $this->assign->addPlugin('bootstrap-switch');
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").bootstrapSwitch({';
                            $this->addJqueryOptions($field);
                        }
                    }
                else
                    foreach ($forms['form']['input'] as &$field)
                        # foreach ($form['input'] as &$field)
                    {
                        // add field values
                        if ($dataFormDb)
                        {
                            if (is_object($this->model) && $this->model->id)
                            {
                                $this->fields_values[$field['name']] = $this->model->{$field['name']};
                            }
                            else
                            {
                                $this->fields_values[$field['name']] = (isset($field['default_value']) ? $field['default_value'] : null);
                            }
                        }

                        if (in_array($field['type'], $this->available_plugins))
                            $this->assign->addPlugin($field['type']);
                        if ($field['type'] == 'selecttwo')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").select2({';
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'inputmask')
                        {
                            $this->assign->addJS('plugins/inputmask/jquery.inputmask.js', true);
                            // check has extenstions
                            if (isset($field['extensions']))
                            {
                                $this->assign->addJS([
                                    'plugins/inputmask/jquery.inputmask.' . $field['extensions'] . '.extensions.js',
                                    'plugins/inputmask/jquery.inputmask.extensions.js',
                                ], true);
                            }

                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").inputmask()';

                        }
                        elseif ($field['type'] == 'colorpicker')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").colorpicker({';
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'datepicker')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").datepicker({';
                            // add options
                            $this->addJqueryOptions($field);

                        }
                        elseif ($field['type'] == 'tree')
                        {
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").treeview({
                        data:' . $field['data'] . ',';
                            // add options
                            $this->assign->addJSVars([
                                'tree_id' => $id,
                                'multiSelect' => $field['multiSelect'],
                                'tree_name' => $field['name'],
                            ]);
                            $this->addJqueryOptions($field);
                        }
                        elseif ($field['type'] == 'textarea' && isset($field['class']) && $field['class'] == 'ckeditor')
                        {
                            $contentsLangDirection = 'ltr';
                            $cke_lang = 'en';
                            $this->assign->addJS('plugins/ckeditor/ckeditor.js', true);
                            if (config('app.rtl'))
                            {
                                $contentsLangDirection = 'rtl';
                                $cke_lang = 'fa';
                            }
                            $this->assign->addJSVars(
                                [
                                    'eldifnder_url' => route('elfinder.ckeditor'),
                                    'contentsLangDirection' => $contentsLangDirection,
                                    'cke_lang' => $cke_lang,
                                ]
                            );

                        }
                        elseif ($field['type'] == 'switch')
                        {
                            $this->assign->addPlugin('bootstrap-switch');
                            $id = (isset($field['id']) ? $field['id'] : $field['name']);
                            $field['javascript'] = '$("#' . $id . '").bootstrapSwitch({';
                            $this->addJqueryOptions($field);
                        }
                    }
                // add id to field_value
            }
            // add more values to array before pass to view
            $this->addMoreValues();
            $this->assign->params([
                'forms' => $this->fields_form,
                'values' => $this->fields_values,
            ]);
        }
    }
    // helper method for add jquery options
    protected function addJqueryOptions(&$field)
    {
        // add options
        if (isset($field['jqueryOptions']))
        {
            $options = '';
            foreach ($field['jqueryOptions'] as $key => $value)
            {
                $options .= $key . ':"' . $value . '",';
            }
            $options = trim($options, ',');
            $field['javascript'] .= $options;
        }
        $field['javascript'] .= '});';
    }

    // do something before update or add
    protected function calcPost($request = false)
    {
        if (!$request && property_exists($this, 'request'))
            $Request = $this->request;
        else
            $Request = $request;
        // switchers
        if (is_array($this->switchers) && count($this->switchers))
        {
            foreach ($this->switchers as $switch)
            {
                if (!$Request->has($switch))
                    $Request->merge([$switch => 0]);
                else
                    $Request->merge([$switch => 1]);
            }
        }
        // check for return request or add to property
        if (!$request && property_exists($this, 'request'))
            $this->request = $Request;
        else
            return $Request;
    }

    /**
     * add more values before render views and calculate data
     */
    protected function addMoreValues()
    {

    }

    /**
     * add data values from session before generateForm
     */
    protected function fillValues()
    {
        // check save or save and stay checked or not
        if ($this->request->old('saveAndStay') || $this->request->old('save'))
            if (count($this->fields_form))
            {
                // add plugins
                foreach ($this->fields_form as &$form)
                {
                    foreach ($form['input'] as &$field)
                    {
                        $this->fields_values[$field['name']] = $this->request->old($field['name']);
                    }
                }
            }
    }
}