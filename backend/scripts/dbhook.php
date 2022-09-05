<?php

require __DIR__ . '/../service/auth/Security.php';

function getConfigurationFile($fileType) {
    $headers         = getallheaders();
    $extClientPreset = getValueFromEnv("EXTERNAL_CLIENT_PRESET");
    $environmentPath = "";

    $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

    if(empty($authHeader)) {
        errorMessage("Invalid access token");        
    }

    if(empty($extClientPreset)) {
        $authStr     = isset($headers['Authorization']) ? $headers['Authorization'] : null;
        $authStr     = str_replace("bearer", "", $authHeader);
        $authStr     = str_replace("Bearer", "", $authStr);
        $environment = json_decode(Security::decrypt(trim($authStr)), true);
        
        if(empty($environment)) {
            errorMessage("Invalid access token");
        }
 
        if(!isset($environment['dbname'])) {
            errorMessage("Invalid access token");
        }
        
        return json_encode($environment);
    } else {
        $environmentPath = __DIR__ . "/../clients/external/$extClientPreset/$fileType";   
    }
    
    if(!file_exists($environmentPath)) {
        errorMessage("Invalid $fileType file");
    }
    
    return file_get_contents($environmentPath);
}

function getClientData() {
    $extClientPreset = getValueFromEnv("EXTERNAL_CLIENT_PRESET");
    $clientDataPath = "";

    if(empty($extClientPreset)) {
        $clientDataPath  = __DIR__ . "/../configs/configs.json"; //SaaS Version
    } else {
        $clientDataPath = __DIR__ . "/../clients/external/$extClientPreset/config.json";   
    }
    
    if(!file_exists($clientDataPath)) {
        errorMessage("Invalid client config file");
    }
    
    return json_decode(file_get_contents($clientDataPath), true);
}

function getValueFromEnv($key, $value = "") {
    if(!file_exists(__DIR__ . "/../env.ini")) {
       errorMessage("Invalid .env file");
    }

    $envFile = fopen(__DIR__ . "/../env.ini", "r");
    while(!feof($envFile)) {
        $line         = fgets($envFile);
        $tmp          = explode('=', $line);
        $currentKey   = $tmp[0];
        $currentValue = trim($tmp[1]);
        if($currentKey == $key) {
            $value = $currentValue;
            break;                
        }
    }
    
    fclose($envFile);
    return $value;
}

function errorMessage($error) {
    echo json_encode([
        'error'   => true,
        'message' => $error,
        'data'    => []
    ]); 
    die;
}