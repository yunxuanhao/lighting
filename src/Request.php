<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-04-01
 * Time: 20:22
 */

namespace Yunxuan\Lighting;


class Request
{
    private static $_files;
    private static $_getParams;
    private static $_postParams;
    private static $_inputParams;

    public static function init()
    {
        self::$_getParams = $_GET;
        self::$_postParams = $_POST;
        self::$_files = $_FILES;
        self::$_inputParams = json_decode(file_get_contents('php://input'), true) ?? [];
    }

    public static function get($key, $default = null)
    {
        return self::$_getParams[$key] ?? $default;
    }

    public static function post($key, $default = null)
    {
        return self::$_postParams[$key] ?? $default;
    }

    public static function input($key, $default = null)
    {
        return self::$_inputParams[$key] ?? $default;
    }

    public static function arg($key, $default = null)
    {
        return self::all()[$key] ?? $default;
    }

    public static function file($key)
    {
        return self::$_files[$key];
    }

    public static function all() : array
    {
        return array_merge(self::$_getParams, self::$_postParams, self::$_inputParams);
    }

}