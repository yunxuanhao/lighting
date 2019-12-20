<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:43
 */

namespace Yunxuan\Lighting;

use Yunxuan\Lighting\Exception\NotFound404Exception;

class Router
{
    const DEFAULT_CONTROLLER = 'Index';
    const DEFAULT_ACTION = 'Index';

    private static $_namespace;
    private static $_rootPath;
    private static $_routePrefix;

    // $_moduleList数组，属于其中中的key，走3层路由，默认为2层路由
    private static $_controllerDirName = 'Controller';
    private static $_controllerNamespaceName = 'Controller';
    private static $_moduleList = [];

    public static $originalUrl;
    public static $url;
    public static $module = '';
    public static $controller;
    public static $action;

    public static function init()
    {
        self::$_routePrefix = config('route.routePrefix');
        self::$originalUrl = ltrim($_SERVER['REQUEST_URI'], self::$_routePrefix);
        self::$url = config('router.change.' . self::$originalUrl) ?? self::$originalUrl;
    }

    public static function setNameSpace($namespace = '')
    {
        self::$_namespace = $namespace;
    }

    public static function setRootPath($rootPath = '')
    {
        self::$_rootPath = $rootPath;
    }

    /**
     * @return mixed
     * @throws NotFound404Exception
     */
    public static function dispatch()
    {
        $urlArr = explode('/', self::$url);
        if (in_array($urlArr[0], self::$_moduleList, true)) {
            // 三级路由
            self::$module = $urlArr[0];
            self::$controller = $urlArr[1] ?: self::DEFAULT_CONTROLLER;
            self::$action = $urlArr[2] ?: self::DEFAULT_ACTION;
        } else {
            // 两级路由
            self::$controller = $urlArr[0] ?: self::DEFAULT_CONTROLLER;
            self::$action = $urlArr[1] ?: self::DEFAULT_ACTION;
        }

        // step3 校验controller和action是否存在，是否可执行（校验文件是否存在和命名空间是否正确）
        $path = DIRECTORY_SEPARATOR .
            (self::$module ? ucfirst(self::$module) . DIRECTORY_SEPARATOR : '').
            ucfirst(self::$controller) . DIRECTORY_SEPARATOR .
            ucfirst(self::$action) ;

        // check filePath
        $filePath = self::$_rootPath . DIRECTORY_SEPARATOR . self::$_controllerDirName . $path . File::PHP_FILE_EXT;
        if(!file_exists($filePath)) {
            throw new NotFound404Exception();
        }

        // check namespace
        $class = self::$_namespace . '\\' . self::$_controllerNamespaceName .
            str_replace(DIRECTORY_SEPARATOR, '\\', $path);
        if(!class_exists($class)) {
            throw new NotFound404Exception();
        }

        return new $class();
    }

    private static function getUrl($routePrefix)
    {
        $originalUrl = ltrim($_SERVER['REQUEST_URI'], $routePrefix);
        return config('router.change.' . $originalUrl) ?? $originalUrl;
    }
}