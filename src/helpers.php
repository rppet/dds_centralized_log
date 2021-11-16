<?php
function encrypt($data, $key, $method = 'aes-128-cbc')
{
    return openssl_encrypt($data, $method, $key, 0);
}

function decrypt($data, $key, $method = 'aes-128-cbc')
{
    return openssl_decrypt($data, $method, $key, 0);
}