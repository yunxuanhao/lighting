<?php

if(!function_exists('config')) {
    function config($key) {
        return \Yunxuan\Lighting\Config::getConfig($key);
    }
}