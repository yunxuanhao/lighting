<?php
/**
 * Created by PhpStorm.
 * User: yunxuan
 * Date: 2019-03-28
 * Time: 19:43
 */

namespace Yunxuan\Lighting;


class Controller
{
    use InstanceTrait;

    protected $request;
    protected $response;

    public function __construct()
    {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
    }

}