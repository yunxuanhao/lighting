<?php
namespace Yunxuan\LightingDemo;

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH . '/vendor/autoload.php';
/**
 * Created by phpstorm.
 * User: yunxuan
 * Email: yunxuan@didiglobal.com
 * Time: 2019/12/20 2:55 下午
 */

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function index()
    {
        \Yunxuan\Lighting\App::run();

    }

    public function testGetRequestTime()
    {
        $requestTime = \Yunxuan\Lighting\App::getInstance()->getRequestTime();
        echo $requestTime;
        $this->assertEquals($requestTime, time());
    }
}