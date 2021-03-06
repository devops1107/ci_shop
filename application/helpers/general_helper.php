<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
    /**
     * This function generate the token for compare with the app token.
     * @param  string $nonce     
     * @param  int $timestamp unix timestamp
     * @return string newly generated token          
     */
    function generateToken($nonce,$timestamp) 
    {    
        $hash_str       = "";
        $secret         = SECRET;
        $private_key    = PRIVATE_KEY;
        $hash_hmac_algo = HASH_HMAC_ALGO;
        $hash_str       = "nonce=".$nonce."&timestamp=".$timestamp."|".$secret;
        //mail('anil@spaceotechnologies.com', 'hash_str_'.time(),print_r($hash_str,true));
        
        $sig = hash_hmac($hash_hmac_algo, $hash_str , $private_key);
        return $sig;
    }

    /**
     * This function validate the token generated by APP.
     * @param  array $data 
     * @return array       
     */
    function validateToken($data) 
    {
        $response = array();
        if(empty($data['token'])) {
            $response['status'] = "0";
            $response['message'] = "Token cannot be blank.";
            return $response;
        }
        if(empty($data['nonce'])) {
            $response['status'] = "0";
            $response['message'] = "Nonce cannot be blank.";
            return $response;
        }
        if(empty($data['timestamp'])) {
            $response['status'] = "0";
            $response['message'] = "Timestamp cannot be blank.";
            return $response;
        }
        $sig = generateToken($data['nonce'],$data['timestamp']);
        if ($sig !== $data['token']) {
           $response['status'] = "0";
           $response["message"] = "Token Invalid.";         
        }
        return $response;
    }
	
?>