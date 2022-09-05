<?php
namespace App;

/*
 *
 * Router
 *
 */

$routesFile = file_get_contents(__DIR__ . "/../src/routes.json");
$routes     = json_decode($routesFile, true);

foreach($routes as $route) {
    $controller = "\\App\\Controller\\".$route['controller'];
    

    if($route['type'] == 'GET') {
        $app->get($route['name'], [$controller, $route['method']]);
    }

    if($route['type'] == 'POST') {
        $app->post($route['name'], [$controller, $route['method']]);
    }
}
