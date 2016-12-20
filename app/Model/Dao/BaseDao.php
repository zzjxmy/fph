<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 15/11/20
 * Time: 下午2:12
 */

namespace App\Model\Dao;


use Illuminate\Database\Eloquent\Model;

class BaseDao extends Model
{
    public $timestamps = false;

    /**
     * 获取一条记录
     * @param $query
     * @param array $columns
     * @param array $order
     * @return mixed
     */
    public function getOne($query, $columns=['*'])
    {
        return $this->whereRaw($query[0], $query[1])->get($columns)->first();
    }

    /**
     * 增加记录
     * @param $data
     * @return mixed
     */
    public function addOne($data)
    {
        return $this->insertGetId($data);
    }

    public function updateOne($option, $data)
    {
        return $this->whereRaw($option[0], $option[1])->update($data);
    }

    public function countNum($option, $field)
    {
        return $this->whereRaw($option[0], $option[1])->count($field);
    }

    public function oneField($query, $field)
    {
        return $this->whereRaw($query[0], $query[1])->pluck($field);
    }

    public function getList($query, $field, $order)
    {
        return $this->whereRaw($query[0], $query[1])->orderBy($order[0], $order[1])->get($field);
    }

    public function pageList($query, $field, $order, $page, $pageSize)
    {
        return $this->whereRaw($query[0], $query[1])
                    ->orderBy($order[0], $order[1])
                    ->skip($page*$pageSize)->take($pageSize)
                    ->get($field);
    }
}