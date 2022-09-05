<?php

class Security {
    const CIPHER_METHOD = "AES-256-CBC";
    const CURRENT_PK    = "VGVrbmlzYUAxOTkwMDUwMQ==";

    public static function encrypt($text) {
        $key        = hash('sha256', base64_decode(self::CURRENT_PK), true);
        $iv         = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($text, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);
        $hash       = hash_hmac('sha256', $ciphertext, $key, true);
        return base64_encode($iv . $hash . $ciphertext);
    }
    
    public static function decrypt($text) {
        $text       = base64_decode($text);
        $key        = hash('sha256', base64_decode(self::CURRENT_PK), true);
        $iv         = substr($text, 0, 16);
        $hash       = substr($text, 16, 32);
        $ciphertext = substr($text, 48);
    
        if(hash_hmac('sha256', $ciphertext, $key, true) !== $hash) 
            return null;
    
        return openssl_decrypt($ciphertext, self::CIPHER_METHOD, $key, OPENSSL_RAW_DATA, $iv);
    }
}


