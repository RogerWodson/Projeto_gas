<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/Security.php';

$app = new \Slim\App;
$app->post('/', function (Request $request, Response $response) {
    $params = $request->getParsedBody();
    $dbname = $params['dbname'];

    $connectionStr = json_encode([
        "driver"        => "oci8",
        "host"          => "",
        "port"          => "",
        "user"          => "/",
        "password"      => "",
        "dbname"        => $dbname,
        "charset"       => "AL32UTF8",
        "service"       => true,
        "sessionMode"   => "OCI_CRED_EXT"
    ]);   

    $token = Security::encrypt($connectionStr);
    return $response->withJson([
        'accessToken' => $token
    ]);
});
$app->run();
