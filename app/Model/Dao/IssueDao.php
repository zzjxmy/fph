<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;



class IssueDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'issue';

    protected $primaryKey = 'id';
    
    
    public function city(){
        return $this->hasOne('App\Model\Dao\CityDao','id','city_id');
    }
    
    public function issueInfo(){
        return $this->hasOne('App\Model\Dao\IssueInfoDao','iid','id');
    }
    
    public function img(){
        return $this->hasOne('App\Model\Dao\ImagesDao','iid','id');
    }
    
}