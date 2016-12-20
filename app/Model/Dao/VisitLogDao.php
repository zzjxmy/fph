<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class VisitLogDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'visit_log';

    protected $primaryKey = 'id';

}