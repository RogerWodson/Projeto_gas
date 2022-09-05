<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class welcome {

    private $connection;

    public function __construct(\Doctrine\DBAL\Connection $connection) {
        $this->connection = $connection;
        
    }

    public function welcome(Request $request, Response $response){
        global $CLIENT_DATA;

        $config  = $CLIENT_DATA;
        $sql     = "select * from empresa";
        $result  = $this->connection->fetchAll($sql);
        $version = $this->getVersionInfo(); 
        
        return $response->withJson([
            'client'       => $config['client'],
            'version'      => $version['version'],
            'revision'     => $version['revision'],
            'date'         => $version['date'],
            'filialCount'  => count($result)
        ]);
    }

    private function getVersionInfo() {
        $file = file_get_contents(__DIR__ . "/../../version.json");
        return json_decode($file, true);
    }

}