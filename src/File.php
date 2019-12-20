<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:40
 */

namespace Yunxuan\Lighting;


class File
{
    const PHP_FILE_EXT = '.php';

    private static $fileCache;

    public static function loadFile($path, $fileExt = self::PHP_FILE_EXT)
    {
        $path .= $fileExt;
        if(self::$fileCache[$path] === null) {
            self::$fileCache[$path] = file_exists($path) ? include $path : [];
        }
        return self::$fileCache[$path];
    }
}