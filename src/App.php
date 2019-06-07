<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-29
 * Time: 18:26
 */

namespace Yunxuan\Lighting;

class App
{
    use InstanceTrait;

    private $_requestTime;

    private $_env;

    private $_module = '';

    protected function __construct()
    {

    }

    public function getRequestTime()
    {
        if($this->_requestTime === null) {
            $this->_requestTime = $_SERVER['REQUEST_TIME'];
        }
        return $this->_requestTime;
    }

    public function setModule($module = '')
    {
        $this->_module = $module;
        return $this;
    }

    public function run()
    {
        // step1 获取当前的module，找到对应的Classes目录

        // step2 分析url，复制到Request，得到controller和action

        // step3 校验controller和action是否存在，是否可执行（校验文件是否存在和命名空间是否正确）

        // step4 exec controller->action （before，main，action）

        // step5 over


    }



}