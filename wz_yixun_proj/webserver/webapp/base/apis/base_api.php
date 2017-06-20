<?php 
/**
 * 提供其他模块调用微店的接口
 */
class Base_api  {
    function __call($func, $args) {
        if (!isset($args[0]['path'])) {
            throw new Exception(sprintf('%s parameter should include model key', $func),1);
        }
        list($dir, $model) = explode('/', $args[0]['path']);
        require_once(sprintf('/data/release/webserver/webapp/base/models/%s/%s.php',$dir,strtolower($model)));
        $obj = new $model();
        
        $params = $args[0]["params"];
        unset($args);
        $len = count($params);
        $args_str = '';
        for($i=0; $i<$len; $i++) {
            $args_str .= '$params['.$i.'],';
        }
        $args_str = trim($args_str,',');
        eval("\$result = \$obj->\$func($args_str);");
       
        return $result;
    }
}
