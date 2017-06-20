<?php 

function str_unique($str, $delimiter=',') {
    $res  = array();
    $temp = explode($delimiter, $str);
    foreach($temp as $v){
        if (empty($v)) continue;
        $res[$v] = $v;
    }
    return implode( $delimiter, $res);
}
function str_to_array($str, $delimiter=','){
    $res  = array();
    $temp = explode($delimiter, $str);
    if (empty($temp)) return array();
    foreach($temp as $v){
        if (empty($v)) continue;
        $res[$v] = $v;
    }
    return $res;
}
function ids_to_name($ids, $map_id2name){
    $id_list = str_to_array($ids);
    $id2name = array();
    foreach ($id_list as $id){
        $id2name[] = $map_id2name[$id];
    }
    return implode('/', $id2name);
}
function user2team($ids, $user2team, $map_id2name){
    $id_list = str_to_array($ids);
    $id2name = array();
    foreach ($id_list as $id){
        $name = $map_id2name[$user2team[$id]];
        $id2name[$name] = $name;
    }
    return implode('/', $id2name);
}
function show_is_contract($is_contract){
    $config = &get_config();
    return $config['dictionaries']['is_contract'][$is_contract];
}
function my_htmlspecialchars(&$v, $key){
   $v = htmlspecialchars($v, ENT_QUOTES);
}

/**
 *
 * gbk转utf8
 * @param unknown_type $filename 源文件名
 * @throws Exception
 * return $newname  新文件名
 */
function gbk2utf8($filename){
	if ( ! $new_str = file_get_contents($filename)){
		return FALSE;
	}
	$new_str = str_replace("\r\n", "\n", $new_str);
	$new_str = iconv("gbk", "utf-8//IGNORE", $new_str);
	if ( ! file_put_contents($filename, $new_str)){
		return FALSE;	
	}
	return $filename;
}

/**
 *
 * Enter description here 解析手机
 * @param mixed $phone
 * return 正常格式的手机号或者false
 */
function parse_phone($phone){
	//提取手机号形如 (+86)151-5811*21 77格式的
	if (preg_match("/[0-9A-Za-z_\-\* \(\)\+\/]+/",$phone,$matches)){
		$arr = str_split($matches[0]);
		foreach ($arr as $k=>$v){
			if ( ! preg_match("/\d+/", $v)){
				unset($arr[$k]);
			}
		}
		$phone = implode("", $arr);
	}

	if (preg_match("/1[3458][0-9]\d{8}/",$phone,$matches2)){
		return $matches2[0];
	}else{
		return false;
	}
}

/**
 *
 * Enter description here 提取浮点数
 * @param $str
 * @throws Exception
 * return mixed
 */
function parse_float($str){
	if (preg_match("/[0-9\.]+/", $str,$matches)){
		return $matches[0];
	}
	return "";
}

/**
 *
 * Enter description here 合并相同键值的数组 以$seperator分割
 * @param $arr 3维数组
 * @param $seperator  分隔符
 * @throws Exception
 */
function array_plus($arr, $seperator){
	$arr2 = array();
	foreach ($arr as $k=>$v){
		foreach ($v as $vk => $vv) {
			foreach ($vv as $vvk => $vvv) {
				$arr2[$vk][$vvk] = isset($arr2[$vk][$vvk]) ? ($arr2[$vk][$vvk] . $seperator . $vvv): $vvv;
				
				$arr2[$vk][$vvk] = trim($arr2[$vk][$vvk],$seperator);
			}
		}
	}
	return $arr2;
}
