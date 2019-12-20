<?php
namespace Yunxuan\Lighting;

/**
 * InstanceTrait
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:51
 */
trait InstanceTrait {
    private static $instance;

    private static function _getInstanceKey($className, $args) : string
    {
        return md5(serialize($args) . $className);
    }

    /**
     * get class instance,support multiple arguments ...
     * @return static
     * @description "return static" is friendly to IDE!
     */
    public static function getInstance()
    {
        $args = func_get_args();
        $className = static::class;
        $key = self::_getInstanceKey($className, $args);
        if(self::$instance[$key] === null) {
            self::$instance[$key] = new $className(...$args);
        }
        return self::$instance[$key];
    }

    public function __clone()
    {

    }
}