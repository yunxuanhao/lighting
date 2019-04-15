<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-27
 * Time: 11:39
 */

namespace Yunxuan\Lighting;


class Config
{
    private static $configCache;

    private static $configPath;

    private static $delimiter = '.';

    public static function setConfigPath($path)
    {
        return self::$configPath = $path;
    }

    public static function getConfig($key)
    {
        if(self::$configCache[$key] === null) {
            self::$configCache[$key] = self::_getConfig($key);
        }
        return self::$configCache[$key];
    }

    private static function _getConfig($key)
    {
        $keyPath = explode(self::$delimiter, $key);
        $path = self::$configPath . DIRECTORY_SEPARATOR . array_shift($keyPath);

        $config = File::loadFile($path);
        foreach ($keyPath as $keyItem) {
            $config = $config[$keyItem];
            if($config === null) {
                break;
            }
        }
        return $config;
    }
}