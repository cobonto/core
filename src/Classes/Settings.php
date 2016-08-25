<?php

namespace Cobonto\Classes;

use Illuminate\Support\Facades\DB;
use LaravelArdent\Ardent\Ardent;

class Settings extends Ardent
{
    protected  $data=[];
    protected $table = 'settings';
    //protected $fillable = ['group_id', 'name', 'value'];
    public static $rules = array(
        'name'                  => 'required|string|unique:settings,name',
        'value'                 => 'required',
    );
    protected $fillable = ['name','value'];
    /**
     * Settings constructor.
     * @param array $attributes
     * @param bool $fill
     */
    public function __construct(array $attributes=[],$fill=false)
    {
        parent::__construct($attributes);
        if($fill)
        {
            /** @var  StdClass $data */
            $data = \DB::table($this->table)->get();
            if(count($data))
            foreach($data as $result)
            {
                $this->data[$result->name] = $result->value;
            }
        }
    }

    /**
     * get by key
     * @param $key
     * @return bool|mixed
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : false;
    }

    public function set($key,$value)
    {
        if (Settings::where('name', $key)->take(1)->update(['value'=>$value])){
            $this->data[$key] = $value;
            return true;
        }
        $setting = new Settings;
        $setting->name = $key;
        $setting->value = $value;
        if ( $setting->save()){
            $this->data[$key] = $value;
            return true;
        }
        return false;
    }
    public function deleteByName($name)
    {
        return \DB::table($this->table)->where('name',$name)->delete();
    }

}