<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class NewsDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'news';

    protected $primaryKey = 'id';

}