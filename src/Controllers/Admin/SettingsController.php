<?php

namespace Cobonto\Controllers\Admin;

use Cobonto\Classes\Translate;
use Cobonto\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Module\Classes\Hook;

class SettingsController extends AdminController
{
    protected $settings;
    protected $located='database';
    protected $wrong=false;
    protected function setProperties()
    {
        parent::setProperties();
        $this->title = $this->lang('settings');
    }

    /*
   |--------------------------------------------------------------------------
   | routes
   |--------------------------------------------------------------------------
   |
   */
    public function settings(Request $reqeust, $tab)
    {
        $html = $this->loadSettings($tab);
        if($this->wrong)
            return $html;
        $this->assign->params([
            'active_settings' => $tab,
            'html' => $html,
        ]);
        $this->tpl = 'settings';
        return parent::view();
    }

    public function load(Request $request)
    {
        $validate = \Validator::make($request->all(), [
            'settings' => 'required|alpha'
        ]);
        if ($validate->fails())
        {
            return response()->json(['status' => 'error', 'msg' => $this->lang('invalid_data')]);
        }
        else
        {
            if (!$html = $this->loadSettings($request->input('settings')))
            {
                return response()->json(['status' => 'error', 'msg' => $this->lang('nodata_for_settings')]);
            }
            else
            {
                return response()->json(['status' => 'success', 'html' => $html]);
            }
        }
    }

    public function updateSettings(Request $request)
    {
       $validate = \Validator::make($request->all(),[
           'settings'=>'alpha',
       ]);
        $settings = $request->input('settings');
        if($validate->fails())
            return redirect($this->getRoute('settings',['settings'=>$settings])->withErrors($this->lang('invalid_data')));
        else
        {

            if(method_exists($this,'save'.ucfirst($settings).'Settings'))
            {

                if($settings=='adminUrl' && $this->{'saveAdminUrlSettings'}($request))
                {
                    return redirect(route('url.changed'));
                }
                elseif($this->{'save'.ucfirst($settings).'Settings'}($request))
                {
                    return redirect($this->getRoute('settings',['settings'=>$settings]))->with('success',$this->lang('update_success'));
                }
                else
                {
                    return redirect($this->getRoute('settings',['settings'=>$settings]))->withErrors($this->errors);
                }
            }
            else
            {
                if(($result = Hook::execute('save'.ucfirst($settings).'Settings',['request'=>$request]))===true)
                {
                    return redirect($this->getRoute('settings',['settings'=>$settings]))->with('success',$this->lang('update_success'));
                }
                else
                {
                    return redirect($this->getRoute('settings',['settings'=>$settings]))->withErrors($request);
                }
            }
        }
    }

    /**
     * ajax actions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxActions(Request $request)
    {
        $validate = \Validator::make($request->all(),[
            'action'=>'string|required',
        ]);
        if($validate->fails())
            return response()->json(['status' => 'error', 'msg' => $this->lang('invalid_data')]);

        $action ='ajaxAction'.ucfirst(camel_case($request->input('action')));
       if(method_exists($this,$action))
            $this->{$action}();
        else
            return response()->json(['status' => 'error', 'msg' => $this->lang('method_not_exists')]);

        return response()->json(['status' => 'success', 'msg' => $this->lang('action_successfully_done')]);
    }

    protected function ajaxActionClearCache()
    {
        \Cache::flush();
    }

    protected function ajaxActionCacheRoute()
    {
        \Artisan::call('route:cache');
    }
    /**
     * load settings for specific tab
     * @param string $tab
     */
    protected function loadSettings($tab)
    {
        if (method_exists($this, 'load' . ucfirst($tab) . 'Settings'))
        {
            $this->{'load' . ucfirst($tab) . 'Settings'}();
            $this->tpl = 'admin.helpers.form.form';
            $this->assign->params([
                'id' => 0,
                'form_url' => $this->getRoute('update'),
                'object' => null,
                'route_list' => false,
            ]);
            $this->fillValues();
            $this->generateForm();
            return $this->view()->render();
        }
        elseif(($html = hook('displayAdminSettingsForm', ['settings' => $tab]))!='')
            return $html;
        /** wrong link or parameter */
        else
        {
            $this->wrong=true;
            return redirect($this->getRoute('settings',['settings'=>'general']))->withErrors('invalid_data');
        }



    }
    /*
  |--------------------------------------------------------------------------
  | Set media
  |--------------------------------------------------------------------------
  |
  */
    protected function setMedia()
    {
        $this->assign->addJSVars([
            'load_settings' => adminRoute('settings.load'),
            'page_url'=>url(config('app.admin_url').'/settings/').'/',
            'ajax_cache_url'=>adminRoute('settings.ajax.actions'),
            'offText'=>$this->lang('no'),
            'onText'=>$this->lang('yes'),
        ]);
        parent::setMedia();
        $this->assign->addCSS('css/bootstrap.vertical-tabs.css');
        $this->assign->addCSS('css/settings.css');
        $this->assign->addPlugin('growl');
        $this->assign->addPlugin('bootstrap-switch');
        $this->assign->addJS('js/settings.js');
    }

    /*
    |--------------------------------------------------------------------------
    | load settings form
    |--------------------------------------------------------------------------
    |
    */
    protected function loadEmailSettings()
    {
        $this->fields_form = [
            [
                'title' => $this->lang('email_settings'),
                'input' => [
                    [
                        'name' => 'MAIL_DRIVER',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('mail_api'),

                    ],
                    [
                        'name' => 'MAIL_HOST',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('mail_host'),
                    ],
                    [
                        'name' => 'settings',
                        'type' => 'hidden',
                       'default_value'=>'email',
                    ],
                    [
                        'name' => 'MAIL_PORT',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('mail_port'),
                    ],
                    [
                        'name' => 'MAIL_USERNAME',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('mail_username'),
                    ],
                    [
                        'name' => 'MAIL_PASSWORD',
                        'type' => 'text',
                        'class' => '',
                        'col' => '6',
                        'title' => $this->lang('mail_password'),
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => $this->lang('save'),
                        'type' => 'submit',
                    ],
                ],
            ],
        ];
    }

    protected function loadGeneralSettings()
    {
        foreach (Translate::getLanguages() as $language)
            $languages[] = ['lang' => $language, 'name' => $language];
        $this->fields_form = [
            [
                'title' => $this->lang('general_settings'),
                'input' => [
                    [
                        'type' => 'select',
                        'title' => $this->l('language'),
                        'name' => 'APP_LOCALE',
                        'col' => 2,
                        'options' =>
                            [
                                'query' => $languages,
                                'key' => 'lang',
                                'name' => 'name',
                            ],
                        'required' => false,
                    ],
                    [
                        'name' => 'settings',
                        'type' => 'hidden',
                        'default_value'=>'general',
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => $this->lang('save'),
                        'type' => 'submit',
                    ],
                ],
            ],
        ];
    }
    protected function loadAdminUrlSettings()
    {
        $this->fields_form = [
            [
                'title' => $this->lang('admin_url_settings'),
                'input' => [
                    [
                        'type' => 'text',
                        'title' => $this->l('url'),
                        'name' => 'APP_ADMIN_URL',
                        'col' => 4,
                        'required' => false,
                    ],
                    [
                        'name' => 'settings',
                        'type' => 'hidden',
                        'default_value'=>'adminUrl',
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => $this->lang('save'),
                        'type' => 'submit',
                    ],
                ],
            ],
        ];
    }
    protected function loadDeploymentSettings()
    {
        $this->fields_form = [
            [
                'title' => $this->lang('deployment_settings'),
                'input' => [
                    [
                        'name' => 'APP_DEBUG',
                        'type' => 'switch',
                        'col' => '6',
                        'class'=>'switch',
                        'title' => $this->lang('debug'),
                        'jqueryOptions' => [
                            'onColor' => 'success',
                            'offColor' => 'danger',
                            'onText' => $this->lang('yes'),
                            'offText' => $this->lang('no'),
                        ]
                    ],
                    [
                        'name' => 'settings',
                        'type' => 'hidden',
                        'default_value'=>'deployment',
                    ],
                ],
                'submit' => [
                    [
                        'name' => 'save',
                        'title' => $this->lang('save'),
                        'type' => 'submit',
                    ],
                ],
                'links'=>[
                    [
                        'name' => $this->l('clear_cache'),
                        'title' => $this->l('clear_cache'),
                        'icon' => 'paint-brush',
                        'link' => '#clear_cache',
                        'id'=>'clear_cache',
                        'class' => 'ajax-btn btn-info'
                    ],
                    [
                        'name' => $this->l('cache_route'),
                        'title' => $this->l('cache_route'),
                        'icon' => 'compress',
                        'link' => '#cache_route',
                        'id'=>'cache_route',
                        'class' => 'ajax-btn btn-primary'
                    ],
                ],
            ],
        ];
    }
    /*
  |--------------------------------------------------------------------------
  | save settings
  |--------------------------------------------------------------------------
  |
  */
    protected function saveEmailSettings(Request $request)
    {
        $params = ['MAIL_DRIVER','MAIL_HOST','MAIL_PORT','MAIL_USERNAME','MAIL_PASSWORD'];
        foreach($params as $key)
        {
            if(!$this->setInEnvironmentFile($key,$request->input($key)))
                return false;
        }
        return true;
    }
    protected function saveAdminUrlSettings(Request $request)
    {
        $old_url = config('app.admin_url');
        $new_url = $request->input('APP_ADMIN_URL');
        if($old_url==$new_url)
            return true;
        else
        {
            $validator= \Validator::make($request->all(),
                ['APP_ADMIN_URL'=>'required|string|between:3,100'],
                ['required'=>transTpl('admin_url_required','customValidation'),
                 'string'=>transTpl('admin_url_must_be_string','customValidation'),
                 'between'=>transTpl('admin_url_between_3_100','customValidation')]);
            if($validator->fails())
            {
                $this->errors = $validator->errors();
                return false;
            }
            app('files')->delete(app()->getCachedRoutesPath());
            \Cache::flush();
            $this->setInEnvironmentFile('APP_ADMIN_URL',$request->input('APP_ADMIN_URL'));
            config(['app.admin_url'=>$request->input('APP_ADMIN_URL')]);
            $this->route_name = config('app.admin_url') . '.' . $this->route . '.';
            return true;
        }
    }
    public function saveGeneralSettings(Request $request)
    {
        $params = ['APP_LOCALE'];
        $validator = \Validator::make($request->all(),
            ['APP_LOCALE'=>'required|alpha'],[
                'required'=>transTpl('lang_required','customValidation'),
                'alpha'=>transTpl('lang_must_alpha','customValidation'),
            ]);
        if($validator->fails())
        {
            $this->errors[] = $validator->errors();
            return false;
        }
        foreach($params as $key)
        {
            if(!$this->setInEnvironmentFile($key,$request->input($key)))
                return false;
        }
        return true;
    }
    public function saveDeploymentSettings(Request $request)
    {
        $this->switchers = array_merge($this->switchers,['APP_DEBUG']);
        $this->calcPost();
        // app_debug $value
        $debug = 'true';
        if($this->request->input('APP_DEBUG')==0)
            $debug = 'false';
        $key = 'APP_DEBUG';
        return file_put_contents(app()->environmentFilePath(), str_replace(
            $key.'='.(env($key)?strval('true'):strval('false')),
            $key.'='.strval($debug),
            file_get_contents(app()->environmentFilePath())
        ));
    }
    protected function fillValues()
    {
        if (count($this->fields_form))
        {
            // add plugins
            foreach ($this->fields_form as $form)
            {
                foreach ($form['input'] as $field)
                {
                    if(!isset($field['default_value']))
                    $this->fields_values[$field['name']] = env($field['name']);
                    else
                        $this->fields_values[$field['name']] = $field['default_value'];
                }
            }
        }
    }

    /**
     * replacement configs
     * @param $key
     * @param $value
     */
    protected function setInEnvironmentFile($key,$value)
    {
       return file_put_contents(app()->environmentFilePath(), str_replace(
            $key.'='.env($key),
            $key.'='.$value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
}