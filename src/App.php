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

    private $_controllerDirName = 'Controller';
    private $_controllerNamespaceName = 'Controller';
    private $_fileExt = '.php';
    private $_execOrder = ['before', 'main', 'after'];

    private $_namespace;
    private $_rootPath;

    // module数组，属于module中的key，走3层路由，默认为2层路由
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

    public function setNameSpace($namespace = '')
    {
        $this->_namespace = $namespace;
        return $this;
    }

    public function setRootPath($rootPath = '')
    {
        $this->_rootPath = $rootPath;
        return $this;
    }

    public function run()
    {
        // step2 分析url，复制到Request，得到controller和action
        list($module, $controller, $action) = Router::dispatch();

        // step3 校验controller和action是否存在，是否可执行（校验文件是否存在和命名空间是否正确）
        $path = DIRECTORY_SEPARATOR . ($module ? ucfirst($module) . DIRECTORY_SEPARATOR : '').
            ucfirst($controller) . DIRECTORY_SEPARATOR .
            ucfirst($action) ;

        // check filePath
        $filePath = $this->_rootPath . DIRECTORY_SEPARATOR .
            $this->_controllerDirName . $path . $this->_fileExt;
        if(!file_exists($filePath)) {
            throw new \NotFound404Exception();
        }

        // check namespace
        $class = $this->_namespace . '\\' . $this->_controllerNamespaceName .
            str_replace(DIRECTORY_SEPARATOR, '\\', $path);
        if(!class_exists($class)) {
            throw new \NotFound404Exception();
        }

        // step4 exec controller->action （before，main，action）
        $o = new $class();
        foreach ($this->_execOrder as $order) {
            if(method_exists($o, $order)) {
                $o->$order();
            }
        }

        // step5 over


    }



}