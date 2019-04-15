<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:43
 */

namespace Yunxuan\Lighting;

class Orm
{
    use InstanceTrait;

    protected static $connPool;

    public function getConn($key)
    {
        if(self::$connPool[$key] === null) {
            self::$connPool[$key] = $this->_getConn($key);
        }
        return self::$connPool[$key];
    }


}