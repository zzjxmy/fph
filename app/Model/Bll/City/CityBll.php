<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/11/20
 * Time: 上午11:48
 */

namespace App\Model\Bll\City;

use App\Model\Dao\CityDao;

class CityBll
{

    public function __construct(CityDao $cityDao)
    {
        $this->cityDao = $cityDao;
    }

    /**
     * @return array|mixed
     * 获取热门城市
     */
    public function getHotCity()
    {

        $query = ['recommend = ?',[1]];
        $columns = ['id','name'];
        $cityList = $this->cityDao->getCity($query, true, $columns);
        $cityList = $cityList ? $cityList->toarray():[];
        return $cityList;
    }
    
    /**
     * 根据城市ID获取城市名字
     * @param $cityId int 城市ID
     * @return NULL|string
     */
    public function getCityNameByCityId($cityId){
        $cityName = $this->cityDao->getOne(['id = ?',[$cityId]],['name']);
        if($cityName){
            return $cityName->name;
        }
        return '';
    }

    /**
     * @param $id
     * @return string
     * 获取完整的地区信息
     */
    public function getAddrsCity($id){
        $city = $this->cityDao->getOne(['id = ?',[$id]],['id','name','spid']);
        $city = $city ? $city->toarray():[];
        $cityNameArr = [];
        if($city['spid']){
            $spidArr = explode('|',$city['spid']);
            if(count($spidArr)>1){
                foreach($spidArr as $key => $cityId){
                    if($cityId && $key > 0){
                        $cityName = $this->cityDao->getOne(['id = ?',[$cityId]],['name']);
                        $cityName = $cityName ? $cityName->name:'';
                        $cityNameArr[] = $cityName;
                    }
                }
            }
        }
        $cityNameArr[] = $city['name'];
        return implode(' ',$cityNameArr);
    }

    /**
     * @return array
     * 获取城市信息列表
     */
    public function getCitybByPid($pid = 0){
        $cityList = $this->cityDao->getList(['pid = ?',[$pid]],['id','name'],['id','asc']);
        return $cityList ? $cityList->toarray():[];
    }



}