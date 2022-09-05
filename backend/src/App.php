<?php
namespace App;

use DI\ContainerBuilder;
use Interop\Container\ContainerInterface;

use App\Util\Security as Security;

class App extends \DI\Bridge\Slim\App {

    protected function configureContainer(ContainerBuilder $builder)
    {
        $definitions = [
            'settings.displayErrorDetails' => true,

            \Doctrine\DBAL\Connection::class => function () {
                

                global $ENVIRONMENT_FILE;

                $environmentFile = $ENVIRONMENT_FILE;
                $environment     = json_decode($environmentFile, true);

                if(empty($environment)) {
                    throw new \Exception("environment not found!");
                }

                $connection = null;

                if($environment['driver'] == 'oci8') {
                    $dbPassword = $environment['password'];
                    if(isset($environment['encryptedPassword']) && $environment['encryptedPassword']) {
                        $dbPassword = Security::decrypt($environment['password']);
                    }
                } 

                global $CLIENT_DATA;
                    $envValue = getValueFromEnv("EXTERNAL_CLIENT_PRESET");
                    $CLIENT_DATA['client'] = empty($envValue) ? $environment['dbname'] : $envValue;
           
                    $connectionParams = [
                            'dbname'   => $environment['dbname'],
                            'user'     => $environment['user'],
                            'password' => $environment['password'],
                            'host'     => $environment['host'],
                            'driver'   => $environment['driver']
                        ];
                    $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

                 //$sql = "alter session set NLS_NUMERIC_CHARACTERS = '.,'";
                 //$connection->executeQuery($sql);
                return $connection;
            }
        ];

        $builder->addDefinitions($definitions);
    }
}