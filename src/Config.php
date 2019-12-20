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
    const CONFIG_MAX_LOOP = 5;

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
        $path = self::$configPath;
        $result = null;
        $loop = 1;
        foreach ($keyPath as $keyI) {
            if($loop++ > self::CONFIG_MAX_LOOP) {
                break;
            }
            $path .=  DIRECTORY_SEPARATOR . $keyI;
            if ($result !== null) {
                if(!isset($result[$keyI])) {
                    return null;
                }
                $result = $result[$keyI];
            } elseif(file_exists($path . File::PHP_FILE_EXT)) {
                $result = File::loadFile($path , File::PHP_FILE_EXT);
            } elseif(!is_dir($path)) {
                break;
            }
        }
        return $result;
    }
}