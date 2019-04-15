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

    public function getRequestTime()
    {
        if($this->_requestTime === null) {
            $this->_requestTime = $_SERVER['REQUEST_TIME'];
        }
        return $this->_requestTime;
    }

    public function run()
    {

    }

}