<?php 
/**
 * @brief 将数字转化为IP
 *
 * @param $num2ip    *
 * @return 
 */
function num2ip($num) {   
    $tmp = (double)$num;
    return sprintf('%u.%u.%u.%u', $tmp & 0xFF, (($tmp >> 8) & 0xFF),
            (($tmp >> 16) & 0xFF), (($tmp >> 24) & 0xFF));
}

/**
 * @brief 将Ip地址转化为整数
 *
 * @param $ip
 *
 * @return 
 */
function ip2num($ip) {
    $n = ip2long($ip);

    /** convert to network order */
    $n =       (($n & 0xFF) << 24)
        | ((($n >> 8) & 0xFF) << 16)
        | ((($n >> 16) & 0xFF) << 8)
        | (($n >> 24) & 0xFF);
    return  $n;
}
/**
 * @brief request unique id，保证一段时间内没有重复的id
 *
 * @return 
 */
function gen_sign_id(){
    $arr = gettimeofday();
    return sprintf('%u',((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) |
                0x80000000));
} 
/**
 * @brief get_ip 
 *
 * @return 
 */
function get_local_ip(){
    $pattern = '/(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,3}|2[0-4]\d|25[0-5])/';
    exec("/sbin/ifconfig", $out, $stats);
    $ips = array();
    if(!empty($out))
    {
        foreach ($out as $v){
            if(preg_match_all($pattern, $v, $matches)){
                return $matches[0][0];
            }
        }
    }
    return '127.0.0.1';

}
function gen_auth_token($user_id, $ip){
    $ip = ip2long($ip) + $user_id;
    return str_replace('.', sprintf('%u%s', $ip, gen_sign_id()), uniqid('', true));
}

/*  vim: set ts=4 sw=4 sts=4 tw=100 noet: */
