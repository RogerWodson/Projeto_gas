<?php
ini_set('memory_limit', '256M');
require __DIR__ . '/../vendor/autoload.php';

$app = new \App\App;

require __DIR__ . '/../src/router.php';
require __DIR__ . '/../scripts/dbhook.php';

$ENVIRONMENT_FILE = getConfigurationFile('environment.json');
$CLIENT_DATA      = getClientData();


$app->run();