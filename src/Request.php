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
    use InstanceTrait;

    private $_header;
    private $_files;
    private $_getParams;
    private $_postParams;

    public function __construct()
    {
        $this->_getParams = $_GET;
        $this->_postParams = $_POST;
        $this->_getParams = $_SERVER;
        $this->_files = $_FILES;
    }

    public function get($key, $default = null)
    {
        return $this->_getParams[$key] ?? $default;
    }

    public function post($key, $default = null)
    {
        return $this->_postParams[$key] ?? $default;
    }

    public function file($key)
    {
        return $this->_files[$key];
    }

    public function all() : array
    {
        return array_merge($this->_getParams, $this->_postParams);
    }

}