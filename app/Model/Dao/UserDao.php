<?php
/**
 * Created by PhpStorm.
 * User: mihailong
 * Date: 16/12/20
 * Time: 上午11:43
 */

namespace App\Model\Dao;


class UserDao extends  BaseDao
{
    protected $connection = 'mysql';

    protected $table = 'user';

    protected $primaryKey = 'id';

}