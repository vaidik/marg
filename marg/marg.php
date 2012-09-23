<?php

include 'includes/Request.php';

$request = new Request();

class Marg {
    public function __construct() {}

    public static function run($routes) {
        global $request;
        $controller_name = '';
        $matches = array();
        foreach ($routes as $pattern => $controller) {
            if (preg_match($pattern, $request->uri, $match)) {
                $controller_name = $controller;
                $matches = $match;
                break;
            }
        }

        if (class_exists($controller_name)) {
            $controller = new $controller_name($matches);

            $method = strtolower($request->verb);
            if (method_exists($controller, $method)) {
                call_user_func(array($controller, $method), $matches);
            }
        } elseif (function_exists($controller_name)) {
            call_user_func($controller_name, $matches);
        } else {
            throw new Exception;
            // raise http exception
        }
    }

};

?>
