<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/11/20
 * Time: 上午11:48
 */

namespace App\Model\Bll\Issue;

use App\Model\Dao\CityDao;
use App\Model\Dao\ImagesDao;
use App\Model\Dao\IssueDao;
use App\Model\Dao\IssueInfoDao;
use Illuminate\Support\Facades\DB;

class IssueBll
{

    public function __construct(IssueDao $issueDao,CityDao $cityDao,IssueInfoDao $issueInfoDao, ImagesDao $imagesDao)
    {
        $this->issueDao = $issueDao;
        $this->cityDao = $cityDao;
        $this->issueInfoDao = $issueInfoDao;
        $this->imagesDao = $imagesDao;
    }


    /**
     * @param int $type
     * @param string $search
     * @return array
     * 项目合作列表
     */
    public function getItemLists($type = 0 ,$search ='')
    {
        if($type){
            $query = $search ? ["status = ? AND check_status = ? AND class = ? AND (title LIKE '%$search%' OR project LIKE '%$search%' ) ",[1,2,$type]]:["status = ? AND check_status = ? AND class = ? ",[1,2,$type]];
        }else{
            $query = $search ? ["status = ? AND check_status = ? AND (class = ? OR class = ? OR class = ? or class = ? ) AND (title LIKE '%$search%' OR project LIKE '%$search%') ",[1,2,4,5,6,7]]:["status = ? AND check_status = ? AND (class = ? OR class = ? OR class = ? OR class = ? )",[1,2,4,5,6,7]];
        }
        $columns    = ['id','class','type','title','city_id','rz_status','total_price','area','com','add_time'];
        $order      = ['add_time','desc'];
        $issueList = $this->issueDao->getList($query, $columns,$order);
        $issueList = $issueList ? $issueList->toarray():[];
        return $this->builData($issueList);
    }

    /**
     * @param int $type
     * @param string $search
     * @return mixed
     * 资产交易列表
     */
    public function getAssetsLists($type = 0 ,$search =''){
        if($type){
            $query = $search ? " AND A.wuye_type = $type AND (B.title LIKE '%$search%' OR B.project LIKE '%$search%')":" AND A.wuye_type = $type";
        }else{
            $query = $search ? " AND (B.title LIKE '%$search%' OR B.project LIKE '%$search%')":"";
        }
        $sql = "SELECT B.id,B.class,B.type,B.title,B.city_id,B.rz_status,B.total_price,B.area,B.com,B.add_time
                FROM fph_issue_info A
                JOIN fph_issue B
                ON A.iid=B.id
                WHERE B.status=:status AND B.check_status=:check_status AND B.class =:class".$query." order by add_time desc";
        $data = DB::connection('mysql')->select($sql,['status'=>1,'check_status'=>2,'class'=>1]);
        if($data){
            foreach($data as $k => $v){
                $data[$k] = (array)$v;
            }
        }else{
            $data = [];
        }
        return $this->builData($data);
    }

    /**
     * @param int $type
     * @param string $search
     * @return array
     * 获取技能服务
     */
    public function getServiceLists($type = 0 ,$search =''){
        if($type){
            $query = $search ? ["status = ? AND check_status = ? AND class = ? AND (title LIKE '%$search%' OR project LIKE '%$search%')",[1,2,$type]]:["status = ? AND check_status = ? AND class = ? ",[1,2,$type]];
        }else{
            $query = $search ? ["status = ? AND check_status = ? AND (class = ? OR class = ? ) AND (title LIKE '%$search%' OR project LIKE '%$search%')",[1,2,2,3]]:["status = ? AND check_status = ? AND (class = ? OR class = ? )",[1,2,2,3]];
        }
        $columns    = ['id','class','type','title','city_id','rz_status','total_price','area','com','add_time'];
        $order      = ['add_time','desc'];
        $issueList = $this->issueDao->getList($query, $columns,$order);
        $issueList = $issueList ? $issueList->toarray():[];
        return $this->builData($issueList);
    }

    private function builData($array){
        foreach($array as $key => $val){
            if(!$val['city_id']){
                $array[$key]['city_name'] = '不限';
            }else{
                $cityName = $this->cityDao->getOne(['id = ?',[$val['city_id']],['name']]);
                $cityName = $cityName ? $cityName->name : '不限';
                $array[$key]['city_name'] = $cityName;
            }
        }
        return $array;
    }

    public function getDatails($id){
        $issue = $this->issueDao->getOne(['id = ? ',[$id]]);
        $issue = $issue ? $issue->toarray() : [];
        $issueInfo = $this->issueInfoDao->getOne(['iid = ? ',[$id]]);
        $issueInfo = $issueInfo ? $issueInfo->toarray() : [];
        $data = array_merge($issue, $issueInfo);
        $urls = $this->imagesDao->getList(['iid = ? AND (type = ? OR type = ?)',[$id, 1, 2]],['title','intro','url','type'],['id','asc']);
        $urls = $urls ? $urls->toarray() : [];
        $imgUrls = $fileUrls = [];
        foreach($urls as $val){
            if($val['type'] == 1){
                $imgUrls[] = $val;
            }elseif($val['type'] == 2){
                $fileUrls[] = $val;
            }
        }
        $data['imgUrls'] = $imgUrls;
        $data['fileUrls'] = $fileUrls;
        return $data;
    }



}