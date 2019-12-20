<?php


namespace Yunxuan\Lighting;


class Response
{
    private static $_jsonData = [];

    public static function json($data)
    {
        self::$_jsonData = $data;
    }

    public static function render()
    {
//        header('Content-Type: application/json');
        echo json_encode(array_merge(getTrace(), ['data' => self::$_jsonData]));
    }
}