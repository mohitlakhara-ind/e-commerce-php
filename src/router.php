<?php

class Route{
    private static $uriList = array();
    private static $uriCallback = array();

    static public function add($uri, $function)
    {
        self::$uriList[] = $uri;
        self::$uriCallback[$uri] = $function;
    }

    static public function submit()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

        // Normalise URI so routing works when the project lives in a subdirectory
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
        if ($basePath !== '' && $basePath !== '.') {
            if (strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
                if ($uri === false || $uri === '') {
                    $uri = '/';
                }
            }
        }

        // Ensure URI starts with a slash for consistent matching
        if ($uri === '' || $uri[0] !== '/') {
            $uri = '/' . ltrim($uri, '/');
        }

        // Normalise requests to index.php so they resolve to the home route
        if ($uri === '/index.php') {
            $uri = '/';
        }
        $doesUriMatch = false;

        foreach(self::$uriList as $u)
        {
            if($u == $uri) {
                $doesUriMatch = true;
                break;
            }
        }

        if($doesUriMatch) {
            call_user_func(self::$uriCallback[$uri]);
        } else {
            http_response_code(404);
            require __DIR__ . '/views/404.php';
        }
    }
}