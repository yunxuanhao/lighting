<?php

if(!function_exists('config')) {
    function config($key)
    {
        return \Yunxuan\Lighting\Config::getConfig($key);
    }
}

if(!function_exists('getTrace')) {
    function getTrace(): array
    {
        return \Yunxuan\Lighting\App::getTrace();
    }
}

if(!function_exists('logInfo')) {
    function logInfo($name, $data)
    {
        \Yunxuan\Lighting\Log::log($name, $data, \Yunxuan\Lighting\Log::LOG_LEVEL_INFO);
    }
}

if(!function_exists('logWarning')) {
    function logWarning($name, $data)
    {
        \Yunxuan\Lighting\Log::log($name, $data, \Yunxuan\Lighting\Log::LOG_LEVEL_WARNING);
    }
}

if(!function_exists('logError')) {
    function logError($name, $data)
    {
        \Yunxuan\Lighting\Log::log($name, $data, \Yunxuan\Lighting\Log::LOG_LEVEL_ERROR);
    }
}
