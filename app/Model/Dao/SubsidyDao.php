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

}