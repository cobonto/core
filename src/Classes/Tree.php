<?php


namespace Cobonto\Classes;


use Illuminate\Database\Eloquent\Collection;

class Tree
{
    protected static $parent='parent';
    /**
     * prepare collection to array for create tree
     * @param Collection $collection
     * @param array $selected_ids
     * @param array $unSelectables
     * @param string $primary
     * @param string $parent_key
     * @param string $name
     * @return array
     */
    public static function prepareArray(Collection $collection,$selected_ids=[],$unSelectables=[],$additons=[],$primary='id',$parent_key='parent_id',$name='name')
    {
        $tree = [];
        foreach($collection as $item)
        {
            $subTree =[];
            $subTree['text']=$item->{$name};
            $subTree['id'] = $item->{$primary};
            if(count($additons))
                foreach($additons as $key)
                    $subTree[$key] = $item->{$key};
            $subTree[self::$parent] = (int)$item->{$parent_key};

            if(in_array($item->{$primary},$selected_ids))
            {
                $subTree['state']['selected'] = true;
                $subTree['state']['checked'] = true;
                $subTree['state']['expanded'] = true;
            }
            if(in_array($item->{$primary},$unSelectables))
                $subTree['state']['disabled']=true;

            $tree[] = $subTree;
        }
        return $tree;
    }
    /**
     * prepare collection to array for create tree
     * @param Collection $collection
     * @param array $selected_ids
     * @param array $unSelectables
     * @param string $primary
     * @param string $parent_key
     * @param string $name
     * @return array
     */
    public static function render(Collection $collection,$selected_ids=[],$unSelectables=[],$additons=[],$primary='id',$parent_key='parent_id',$name='name')
    {
        $results = self::prepareArray($collection,$selected_ids,$unSelectables,$additons,$primary,$parent_key,$name);
        $new = [];
        foreach ($results as $result){
            $new[$result[self::$parent]][] = $result;
        }
        return self::createTree($new, [$results[0]]);
    }
    // create tree from array
    protected static function createTree(&$list, $parent,$primary='id',$children='nodes')
    {
        $tree = array();
        foreach ($parent as $k=>$l){
            if(isset($list[$l[$primary]])){
                $l[$children] = self::createTree($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }
}