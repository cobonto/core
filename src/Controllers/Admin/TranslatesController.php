<?php
/**
 * Created by PhpStorm.
 * User: Sharif
 * Date: 11/15/2016
 * Time: 9:04 AM
 */

namespace Cobonto\Controllers\Admin;

use Cobonto\Classes\Translate;
use Cobonto\Controllers\AdminController;
use Illuminate\Http\Request;
use Module\Classes\Module;

class TranslatesController extends AdminController
{
    protected $environments;
    protected $file;
    protected $environment;
    protected $language;

    protected function setProperties()
    {
        $this->tpl = $this->theme.'.translate.main';
        $this->route = 'translates';
        $this->title = $this->lang('translate');
        $this->environments = [
            'core'=>$this->lang('core_translate'),'module'=>$this->lang('module_translate'),
            'front'=>$this->lang('front_translate'),
        ];
        parent::setProperties();
    }

    protected function index()
    {
        $this->assign->addJSVars([
            'translate_environment'=>$this->route('environment'),
        ]);
        // get translate folders
        $this->assign->params([
            'languages' => Translate::getLanguages(),
            'route'=>$this->route,
            'environment'=>$this->environments,
        ]);
        return parent::view();
    }
    protected function setMedia()
    {
        parent::setMedia();
        $this->assign->addJS('js/translate.js');
        $this->assign->addPlugin('growl');
        $this->assign->addPlugin('selecttwo');
    }

    protected function loadEnvironment(Request $request)
    {
        $validate = \Validator::make($request->all(),[
            'environment'=>'alpha|required',
            'language'=>'alpha|required',
        ]);
        if($validate->fails())
        {
            // unknown and return error
            return response()->json(['status'=>'error','msg'=>implode("\n",$validate->errors())]);
        }
        else
        {
            $environment = $request->input('environment');
            $language = $request->input('language');
            if($environment=='core')
                $files = Translate::getCoreFiles($language);
            elseif($environment=='module')
            {
                $modules  =  Module::getModulesByFile('translate'.DIRECTORY_SEPARATOR.$language.'.php');
                foreach($modules as $module)
                    $files[] = $module['name'];
            }

            elseif($environment=='front')
            {
                return response()->json(['status'=>'error','msg'=>'Under develop']);
            }
            else
            {
                // unknown and return error
                return response()->json(['status'=>'error','msg'=>$this->lang('unknown_environment')]);
            }
            return response()->json(['status'=>'success','files'=>$files]);
        }
    }
    protected function loadFile(Request $request)
    {
        $this->tpl = $this->theme.'.translate.file';
        if(!$this->loadData($request))
        {
            return redirect($this->getRoute('index'))->withErrors($this->errors);
        }
        else
        {

            $path = $this->getFilePath();
            if(!\File::exists($path))
            {
                $this->errors[] = $this->lang('this_file_not_found');
                return redirect($this->getRoute('index'))->withErrors($this->errors);
            }
            else
            {
                $this->assign->params(
                    [
                        'data'=>Translate::getFile($path),
                        'file'=>$this->file,
                        'language'=>$this->language,
                        'environments'=>$this->environments,
                        'environment'=>$this->environment
                    ]);
            }
            return parent::view();
        }

    }

    protected function saveFile(Request $request)
    {
        if(!$this->loadData($request))
        {
            return redirect($this->getRoute('index'))->withErrors($this->errors);
        }
        else
        {
            $data = $request->input('trans');
            $path = $this->getFilePath();
            if(!\File::exists($path))
            {
                $this->errors[] = $this->lang('this_file_not_found');
                return redirect($this->getRoute('index'))->withErrors($this->errors);
            }
            else
            {
                $original_data = Translate::getFile($path);
                foreach($original_data as $key=>$value)
                {
                    if(is_array($value))
                    {
                        foreach($value as $subKey=>$subValue)
                        {
                            if(is_string($subValue))
                                if(isset($data[$key][$subKey]))
                                    $original_data[$key][$subKey]= $data[$key][$subKey];
                        }
                    }
                    else
                    {
                        if(isset($data[$key]))
                        $original_data[$key]= $data[$key];
                    }
                }
                // save file
                if(Translate::saveFile($path,$original_data)==false)
                {
                    $this->errors[] = $this->lang('problem_in_save_file');
                    return redirect($this->getRoute('index'))->withErrors($this->errors);
                }
                else
                    return $this->redirect($this->lang('update_success'));
            }
        }

    }
    protected function loadData(Request $request)
    {
        $validate = \Validator::make($request->all(),[
            'environment'=>'alpha|required',
            'language'=>'alpha|required',
            'file'=>'string|required'
        ]);
        if($validate->fails())
        {
            $this->errors = $validate->errors();
            return false;
        }
        else
        {
            $this->environment = $request->input('environment');
            $this->language = $request->input('language');
            $this->file = $request->input('file');
            return true;
        }
    }
    protected function getFilePath()
    {
        $path = false;
        if($this->environment=='core')
            $path = resource_path('lang'.DIRECTORY_SEPARATOR.$this->language.DIRECTORY_SEPARATOR.$this->file);
        elseif($this->environment=='module')
            $path = app_path('Modules'.DIRECTORY_SEPARATOR.$this->file.DIRECTORY_SEPARATOR.'translate'.DIRECTORY_SEPARATOR.$this->language.'.php');
        return $path;
    }
}