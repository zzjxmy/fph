<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class SubsidyDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'subsidy';

    protected $primaryKey = 'id';
    
    
    /**
     * 定义执行前的监听
     * UserDao constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = []){
        parent::__construct($attributes);
        self::saving(function(){
            $this->add_time = time();
        });
    }
    
    /**
     * 统计订阅人数
     * @param $type
     * @return mixed
     */
    public static function countType($type){
        return self::where('type',$type)->count();
    }
}