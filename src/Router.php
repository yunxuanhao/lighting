<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:43
 */

namespace Yunxuan\Lighting;

class Router
{
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'Index';

    // $_moduleList数组，属于其中中的key，走3层路由，默认为2层路由
    private static $_moduleList = [];
    private static $_routePrefix = '';

    public static $originalUrl;

    public static $module = '';
    public static $controller;
    public static $action;

    public static function setModule($moduleList = [])
    {
        self::$_moduleList = $moduleList;
    }

    public static function setRoutePrefix($routePrefix = '')
    {
        self::$_routePrefix = $routePrefix;
    }

    public static function dispatch()
    {
        self::deal();
        return [
            self::$module,
            self::$controller,
            self::$action,
        ];
    }

    private static function deal()
    {
        $url = self::getUrl();
        $urlArr = explode('/', $url);
        if (in_array($urlArr[0], self::$_moduleList)) {
            // 三级路由
            self::$module = $urlArr[0];
            self::$controller = $urlArr[1] ?: self::DEFAULT_CONTROLLER;
            self::$action = $urlArr[2] ?: self::DEFAULT_ACTION;
        } else {
            // 两级路由
            self::$controller = $urlArr[0] ?: self::DEFAULT_CONTROLLER;
            self::$action = $urlArr[1] ?: self::DEFAULT_ACTION;
        }
    }

    private static function getUrl()
    {
        self::$originalUrl = ltrim($_SERVER['REQUEST_URI'], self::$_routePrefix);
        // todo 预留以后有路由改写
        return self::$originalUrl;
    }


}