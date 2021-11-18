<?php
/**
 * Notes:
 * @param $data
 * @param $key
 * @param string $method
 * @return false|string
 * DateTime:2021/11/16 5:45 下午
 */
if (!function_exists('encrypt_data')){
    function encrypt_data($data, $key, $method = 'aes-128-cbc')
    {
        if (in_array($method, openssl_get_cipher_methods())){
            $ivlen = openssl_cipher_iv_length($method);
            $iv = openssl_random_pseudo_bytes($ivlen);
            return openssl_encrypt($data, $method, $key, 0, $iv);
        }
        return $data;
    }
}

/**
 * Notes:
 * @param $data
 * @param $key
 * @param string $method
 * @return false|string
 * DateTime:2021/11/16 5:45 下午
 */
if (!function_exists('decrypt_data')){
    function decrypt_data($data, $key, $method = 'aes-128-cbc')
    {
        if (in_array($method, openssl_get_cipher_methods())){
            $ivlen = openssl_cipher_iv_length($method);
            $iv = substr($data, 0, $ivlen);
            $data = substr($data, $ivlen);
            return openssl_decrypt($data, $method, $key, 0, $iv);
        }
        return $data;
    }
}

/**
 * Notes:
 * @param $params
 * @param $filterArray
 * @return mixed
 * DateTime:2021/11/18 3:36 下午
 */
if (!function_exists('filter_params')){
    function filter_params($params, $filterArray)
    {
        if (!is_array($params)){
            $params = json_decode($params, true);
        }
        if (!$params){
            return [];
        }
        foreach ($params as $key => $param){
            if (is_array($param)){
                filter_params($param, $filterArray);
            } else{
                $filterLength = ceil(strlen($param) / 3);
                if (in_array($key, $filterArray)){
                    $params[$key] = substr_replace($param, str_pad('', $filterLength, '*'), 0, $filterLength);
                }
            }
        }

        return $params;
    }
}
