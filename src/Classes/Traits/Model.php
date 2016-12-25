<?php
/**
 * Created by PhpStorm.
 * User: fara
 * Date: 12/22/2016
 * Time: 7:20 PM
 */

namespace Cobonto\Classes\Traits;


trait Model
{
    /** @var string position column name
     */
    protected $position_column='position';
    public function getHighestPosition()
    {
        return \DB::table($this->table)->orderBy($this->position_column,'DESC')->first([$this->position_column]);
    }
}