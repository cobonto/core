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

    /**
     * get highest position of table
     * @return mixed|static
     */
    public function getHighestPosition()
    {
        $data = \DB::table($this->table)->orderBy($this->position_column,'DESC')->first([$this->position_column]);
        if(!$data)
            return 0;
        else
            $data->{$this->position_column};
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}