<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class CityDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'city';

    protected $primaryKey = 'id';



    /**
     * @param $query
     * @param $multi
     * @param array $columns
     * @return mixed
     */
    public function getCity($query, $multi, $columns = ['*'])
    {
        if ($multi)
        {
            $data = $this->whereRaw($query[0], $query[1])->orderBy('id','asc')->get($columns);
        } else {
            $data = $this->whereRaw($query[0], $query[1])->get($columns)->first();
        }
        return $data;
    }
    
}