<?php

namespace App;

class AuthMW {
    /**
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    
    public function __invoke($request, $response, $next) {
        
        $requestHeaders = getallheaders();
        $httpAuth       = isset($requestHeaders['Authorization']) ? $requestHeaders['Authorization'] : null;
        $token          = $this->getBearerToken($httpAuth);
    
        //saas token
        if(!empty($token)) {
            return $next($request, $response);
        }
    
        if(empty($token) || (!$this->isValidToken($token))) {
            return $response->withJson([
                'success' => false,
                'message' => "Invalid access token!",
                'result'  => []
            ]); 
        } 
        
        else return $next($request, $response);
    }
    
    private function getBearerToken($httpAuth) {
        $token = str_replace("bearer", "", $httpAuth);
        $token = str_replace("Bearer", "", $token);
        return trim($token);
    }
    
    private function isValidToken($token) {
        global $CLIENT_DATA;
        $config      = $CLIENT_DATA;
        $accessToken = $config['accessToken'];
        return ($accessToken == $token);
    }
}