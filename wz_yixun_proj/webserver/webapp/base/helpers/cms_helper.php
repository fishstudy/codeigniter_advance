<?php
/**
 * 
 * curl 向接口发送post数据
 * Enter description here ...
 */
function  curl_post($url='http://icw.com/index.php',$post_data=array(),$cookie=array()){
	$result = array('is_sucess'=>'false','results'=>array());
	$josn_str = json_encode($post_data);
	$data = array('josn_str'=>$josn_str);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	if(!empty($cookie)){
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	}
	$output = curl_exec($ch);
	if ($output === FALSE) {
   		 $result['results'] =  curl_error($ch);
	}else {
		$result['is_sucess'] = 'true';
		$result['results']    = $output;
		
	}
	curl_close($ch);
	return $result;
}
?>
