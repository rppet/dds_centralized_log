<?php
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
