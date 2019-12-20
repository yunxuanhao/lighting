<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-29
 * Time: 18:26
 */

namespace Yunxuan\Lighting;

use Yunxuan\Lighting\Exception\NotFound404Exception;

class App
{
    private static $_trace;

    private static $_execOrder = ['before', 'main', 'after'];

    // app 初始化
    private static function init()
    {
        Request::init();
        Router::init();
        self::setRequestTime();
        self::setTraceId();
        self::setTimezone();
        self::logRequest();
    }

    private static function setRoute()
    {

    }

    // 初始化请求时间
    private static function setRequestTime()
    {
        self::$_trace['requestTime'] = $_SERVER['REQUEST_TIME'];
    }

    // 初始化请求traceId，作为请求唯一识别号
    private static function setTraceId()
    {
        $reqip = '127.0.0.1';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $reqip = $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $reqip = $_SERVER['SERVER_ADDR'];
        }
        $time = gettimeofday();
        $time = $time['sec'] + $time['usec'];
        $rand = mt_rand();
        $ip = ip2long($reqip);
        self::$_trace['traceId'] = self::_idToHex($ip ^ $time) . self::_idToHex($rand);
    }

    public static function getTrace(): array
    {
        return self::$_trace;
    }

    private static function _idToHex($id): string
    {
        return sprintf('%08s', dechex($id));
    }

    private static function setTimezone()
    {
        $timezone = 'Asia/Shanghai';
        if($v = Request::arg('timezone')) {
            $timezone = $v;
        } elseif ($v = config('app.timezone')) {
            $timezone = $v;
        }
        date_default_timezone_set($timezone);
    }

    public static function logRequest()
    {

    }

    public static function logResponse()
    {

    }

    private static function final()
    {
        self::logResponse();
        Response::render();
    }

    public static function run()
    {
        // step1 初始化请求信息
        self::init();

        try {
            // step2 分析url，得到action对象
            $o = Router::dispatch();

            // step3 exec（before，main，action）
            foreach (self::$_execOrder as $order) {
                if(method_exists($o, $order)) {
                    $res = $o->$order();
                }
            }
        } catch (NotFound404Exception $e) {
            Response::json([
                'errCode' => '10000',
                'errMessage' => '404 NotFound',
            ]);
        }

        // step5 over
        self::final();
    }
}