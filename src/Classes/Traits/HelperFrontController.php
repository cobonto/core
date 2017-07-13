<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 7/10/2017
 * Time: 4:04 PM
 */

namespace Cobonto\Classes\Traits;
use Carbon\Carbon;
use Cobonto\Classes\Traits\HelperForm;
use Cobonto\Classes\Traits\HelperList;
use Cobonto\Classes\Traits\SimpleHelperList;
use Cobonto\Controllers\ModuleFrontController;

trait HelperFrontController
{

    protected $table;
    protected $model;
    protected $model_name;
    protected $title;

    protected $request;
    use HelperList;
    use SimpleHelperList;
    use HelperForm;

    /**
     * @var string $meta_title
     */
    protected $meta_title = false;
    /**
     * @var string $meta_description
     */
    protected $meta_description = false;

    public $user;


    public function index()
    {
        $this->setMedia();
        // fill fields list
        $this->fieldList();
        // check permission for actions
        if (count($this->fields_list))
        {
            $this->generateList();
        }
        return $this->view();
    }

    public function view()
    {
        $this->assign->params([
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'user' => $this->user,
            'title' => $this->title,
            'route_name' =>  $this->route . '.',
            'controller' => $this,
            'breadcrumb' => $this->breadcrumb(),
        ]);

        return parent::view();
    }

    protected function setMedia()
    {
        parent::setMedia();
        // css
        $this->module->addCSS([
            'front/global.css',
        ]);
        // js
        $this->module->addJS([
            'front/global.js',
        ]);
        $this->assign->addPlugin('handlebars');
        $this->assign->addJSVars([
            'user_id'=>$this->user->id,
        ]);
    }

    /**
     * create route
     * @param $route
     * @param array $params
     * @return string
     */
    public function route($route, $params = [], $full = true)
    {
        if ($full)
            return route( $this->route . '.' . $route, $params);
        else
            return  $this->route . '.' . $route;
    }

    protected function listQuery()
    {
        if ($this->sql == false)
        {
            $select = [];
            foreach ($this->fields_list as $field => $options)
            {
                // select
                if (!isset($options['sql_method']))
                    $select[] = isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field;
                // sql method like concat and....
                else
                    $select[] = \DB::raw(isset($options['real_field']) ? $options['real_field'] . ' as ' . $field : $field);
            }
            /**
             * @var \DB
             */
            $this->sql = \DB::table($this->table . ' AS a')->select($select);
        }
        return $this->sql;
    }

    protected function pagination()
    {
        $perPage = false;
        if (!$perPage)
            $perPage = 20;
        $this->assign->params(['per_page' => $perPage]);
        return $this->sql->paginate($perPage);
    }

    protected function listTitle($titles = [], $id = 1)
    {
        return false;
    }

    protected function create()
    {
        $this->setMedia();
        $this->loadObject();
        $this->fieldForm();
        // add some variable for view
        // fill value if exists in session
        $this->fillValues();
        $this->generateForm();
        $this->assign->params([
            'id' => 0,
            'form_url' => $this->route('store'),
            'object' => $this->model ?: null,
            'route_list' => $this->route('index'),
        ]);
        return $this->view();
    }

    /**
     * load object
     * @param int|bool $id ;
     * @param bool|false $force
     * @return bool|void
     */
    protected function loadObject($id = false, $force = false)
    {

        if (is_object($this->model))
            return true;
        if (!$this->model_name)
            return false;
        else
        {

            $model = $this->prefix_model . $this->model_name;
            dd($model);
            if (class_exists($model))
            {
                if (!$id && $force)
                    return false;
                elseif ($id)
                {
                    $this->model = $model::find($id);
                    if ($force && !$this->model->id)
                        throw new InvalidModelException($this->model);
                }
                elseif (!$id && !$force)
                    $this->model = new $model;

            }
            return true;
        }
    }
    protected function filters()
    {
        $filters = \Cache::get($this->route('filter',[],false));
        if (count($this->fields_list) && $filters &&  is_array($filters))
        {
            foreach ($filters as $field=>$filter)
            {
                if($filter['type']=='date')
                {
                    $date = explode('/',$filter['value']);
                    $filter['value'] = Carbon::createFromDate($date[2],$date[0],$date[1]);
                }
                // calculate name

                if(isset($this->fields_list[$filter['name']]) && isset($this->fields_list[$filter['name']]['real_field']))
                    $name = $this->fields_list[$filter['name']]['real_field'];
                else
                    $name = $filter['name'];
                $this->sql->where($name,$filter['condition'],$filter['value']);
            }
        }
        $this->assign->params(['filter_values'=>$filters]);
    }
}