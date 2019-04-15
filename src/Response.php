<?php


namespace Yunxuan\Lighting;


class Response
{
    use InstanceTrait;

    /**
     * @param $data
     */
    public function json($data) : void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}