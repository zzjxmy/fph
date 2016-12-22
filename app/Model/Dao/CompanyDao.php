<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class CompanyDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'Company';

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
        
        self::updating(function(){
            $this->update_time = time();
        });
    }

}