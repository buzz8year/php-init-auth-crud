<?php

namespace utils;

class Url
{
    public static function getBasePath()
    {
        $https = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            || (getenv('HTTP_X_FORWARDED_PROTO') === 'https')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

        $protocol = $https 
            ? 'https://' 
            : 'http://';
        
        $domainName = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] 
            ? $_SERVER['HTTP_HOST'] 
            : 'localhost';

        $public = '/public/';

        // WARNING: filter_var() returns (bool)false if filter is not validated
        return filter_var($protocol . $domainName . $public, FILTER_VALIDATE_URL);        
    }

    public static function getCurrentPath()
    {
        $basePath = self::getBasePath();

        $controller = rtrim($_GET['r'] ?? DEFAULT_CONTROLLER_NAME, '/');

        $controllerMethod = sprintf('?r=%s', $controller);

        if (!empty($_GET['id'])) 
            $controllerMethod .= sprintf('&id=%s', $_GET['id']);

        // WARNING: filter_var() returns (bool)false if filter is not validated, - rethink...
        return filter_var($basePath . $controllerMethod, FILTER_VALIDATE_URL);
    }

}