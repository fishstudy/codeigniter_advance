<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 14-10-30
 * Time: 下午6:39
 */

/**
 * Class CI_Safe
 * 加密解密类, 该算法仅支持加密数字
 * @加密原则 标记长度 + 补位 + 数字替换
 * @author Frank
 */
class CI_Safe
{
    const BASE_STRING = "B1u3b8cA0CsakVjqUeWgQxSyJDhoG2YLrIEKwTizPXmNH6nRtZOMd54Flpvf7";
    const DEFAULT_KEY = 3654.5415412812;
    const DEFAULT_LEN = 10;
    private static $_key, $_length, $_codelen, $_codenums, $_codeext;

    private static function _init($length = self::DEFAULT_LEN,  $key = self::DEFAULT_KEY) {
        self::$_key       = $key;
        self::$_length   = $length;
        self::$_codelen     = substr(self::BASE_STRING, 0, self::$_length);
        self::$_codenums = substr(self::BASE_STRING, self::$_length, $length + 1);
        self::$_codeext     = substr(self::BASE_STRING, self::$_length + ($length + 1));
    }

    public static function idEncode($nums,  $length = self::DEFAULT_LEN,  $key = self::DEFAULT_KEY) {
        self::_init($length, $key);
        $rtn = "";
        $numslen = strlen($nums);
        $begin = substr(self::$_codelen, $numslen - 1, 1);
        $extlen = self::$_length - $numslen - 1;
        $temp = str_replace('.', '', $nums / self::$_key);
        $temp = substr($temp,-$extlen);
        $arrextTemp = str_split(self::$_codeext);
        $arrext = str_split($temp);

        foreach ($arrext as $v) {
            $rtn .= $arrextTemp[$v];
        }

        $arrnumsTemp = str_split(self::$_codenums);
        $arrnums = str_split($nums);
        foreach ($arrnums as $v) {
            $rtn .= $arrnumsTemp[$v];
        }

        return $begin . $rtn;
    }

    public static function idDecode($code,  $length = self::DEFAULT_LEN,  $key = self::DEFAULT_KEY) {
        self::_init($length, $key);
        $begin = substr($code, 0, 1);
        $rtn = '';
        $len = strpos(self::$_codelen, $begin);
        if ($len!== false) {
            $len++;
            $arrnums = str_split(substr($code, -$len));
            foreach ($arrnums as $v) {
                $rtn .= strpos(self::$_codenums, $v);
            }
        }
        return $rtn;
    }
}
