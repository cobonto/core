<?php
/**
 * Created by PhpStorm.
 * User: sharif ahrari
 * Date: 7/16/2016
 * Time: 7:32 PM
 */

namespace Cobonto\Classes\Traits;


use Illuminate\Support\Facades\Input;

trait HelperForm
{
    /** @var  array $fields form */
    protected $fields_form = [];
    /** @var  array $fields form */
    protected $fields_values = [];
    /** @var array $tpl_form */
    private $tpl_form = 'admin.helpers.form.main';
    /** @var array available plugin for form */
    private $available_plugins = [
        'selecttwo',
        'colorpicker',
        'datepicker',
        'ckeditor',
    ];
    /** @var array list of switchers */
    protected $switchers = ['active'];
    /**
     * create form with helper form
     */
    protected function generateForm()
    {
        if (count($this->fields_form))
        {
            // add plugins
            foreach ($this->fields_form as &$form)
            {
                foreach ($form['input'] as &$field)
                {
                    // add field values
                    if(is_object($this->model) && $this->model->id)
                    {
                        $this->fields_values[$field['name']]=$this->model->{$field['name']};
                    }
                    else
                    {
                        $this->fields_values[$field['name']] = (isset($field['default_value'])?:null);
                    }
                    if (in_array($field['type'], $this->available_plugins))
                        app('assign')->addPlugin($field['type']);
                    if ($field['type'] == 'selecttwo')
                    {
                        $id = (isset($field['id']) ? $field['id'] : $field['name']);
                        $field['javascript'] = '$("#' . $id . '").select2({';
                        $this->addJqueryOptions($field);
                    }
                    elseif ($field['type'] == 'inputmask')
                    {
                        app('assign')->addJS('plugins/inputmask/jquery.inputmask.js');
                        // check has extenstions
                        if (isset($field['extensions']))
                        {
                            app('assign')->addJS([
                                'plugins/inputmask/jquery.inputmask.' . $field['extensions'] . '.extensions.js',
                                'plugins/inputmask/jquery.inputmask.extensions.js',
                            ]);
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
                    elseif ($field['type'] == 'textarea' && isset($field['class']) && $field['class'] == 'ckeditor')
                    {
                        app('assign')->addJS('plugins/ckeditor/ckeditor.js');

                    }
                    elseif ($field['type'] == 'switch')
                    {
                        app('assign')->addPlugin('bootstrap-switch');
                        $id = (isset($field['id']) ? $field['id'] : $field['name']);
                        $field['javascript'] = '$("#' . $id . '").bootstrapSwitch({';
                        $this->addJqueryOptions($field);
                    }
                }
                // add id to field_value
            }
            app('assign')->view([
                'forms'=>$this->fields_form,
                'values'=>$this->fields_values,
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
            trim($options, ',');
            $field['javascript'] .= $options;
        }
        $field['javascript'] .= '});';
    }
    // do something before update or add
    protected function calcPost()
    {
        // switchers
        if(is_array($this->switchers) && count($this->switchers))
        {
            foreach($this->switchers as $switch)
            {
                if(!$this->request->has($switch))
                    $this->request->merge([$switch=>0]);
                else
                    $this->request->merge([$switch=>1]);
            }
        }
    }
}