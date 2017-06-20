<?php
///usr/local/php/bin/php /data/release/webapp/third/creater/creater.php weidian  daos  article
 /*
  * 文件自动生成器
  *useage: php generator.php weidian  daos  article
  *
  */

 ini_set('date.timezone','Asia/Shanghai');
 ini_set('display_errors',1);
 error_reporting(E_ALL);
 define("CREATBASE", dirname(__FILE__).'/');
 require_once CREATBASE.'configs/base.php';
 require_once CREATBASE.'helper.php';
 define('SMARTY_DIR', dirname(CREATBASE).'/Smarty/libs/');
 
 require(SMARTY_DIR .  '/Smarty.class.php');
 $module = $argv[1]; 
 $layer  = isset($argv[2]) ? $argv[2] : ''; 
 $table  = isset($argv[3]) ? $argv[3] :  ''; 
 $gen    = new Generator($module, $table, $layer);
 $gen->execute();

 class Generator {
     protected $_module;
     static    $_map = array(
     			//'modle' =>array("database",'prefix',"realpath");
     			'base'   => array('weidian', 'Bs', '/data/release/webserver/webapp/base'),
             );
     static $_gen_code = array();
     protected $_prefix;
     protected $_db;
     protected $_smarty;
     protected $_tpl_data;
     protected $_tpl;
     protected $_save_path;
     protected $_table='';
     protected $_layer= '';
     function __construct($module, $table='', $layer=''){
         $this->_smarty = new Smarty();
         $this->_smarty->left_delimiter  ='{{';
         $this->_smarty->right_delimiter ='}}';
         $this->_smarty->force_compile = true;
         $this->_smarty->debugging = false;
         $this->_smarty->caching = false;
         $this->_smarty->cache_lifetime = 120;
         $this->_module = $module;
         list($this->_db, $this->_prefix, $this->_save_path) = self :: $_map[$module];
         $this->_table = $table;
         $this->_layer = $layer;
         $layers_ary = array(
         	 'models'=> 'model.tpl.php', 
             'daos'=> 'dao.tpl.php',
             'controllers'=>'controllers.tpl.php', 
             'logics'=> 'logics.tpl.php',
         );
         foreach($layers_ary as $m=>$v){
         	self::$_gen_code[$m] = CREATBASE.'templates/'.$v;
         }
     }
     function execute(){
         foreach (self::$_gen_code as $k=>$v){
             if ($this->_layer !='' && $k!= $this->_layer) continue;
             $func = sprintf('gen_%s_data', $k);
             $this->{$func}($v);
             //break;
         }
     }
     function gen_controllers_data($tpl){
         global $eh_basedb, $g_controller_map, $g_filter_tables;
         foreach ($g_controller_map[$this->_module] as $table=>$controller_map){
             if ($this->_table !='' && $table!= $this->_table) continue;
             $this->_tpl_data = array(
                'NAME'                   => ucfirst($controller_map ['controller']), 
                'CONTROLLER'             => $controller_map ['controller'], 
                'LOGIC'                  => $controller_map['LOGIC'],
                //'SUGGEST'                => $controller_map['SUGGEST'],
                'ILIST_ITEM'             => isset($controller_map['ILIST_ITEM']) ? $controller_map['ILIST_ITEM']: array(),
                'ILIST_ITEM2'            => isset($controller_map['ILIST_ITEM2']) ? $controller_map['ILIST_ITEM2']: array(),
                'SEARCH_CONDITIONS'      => isset($controller_map['SEARCH_CONDITIONS']) ? $controller_map['SEARCH_CONDITIONS']: array(),
                'MIN_FILL_DATA'          => isset($controller_map['MIN_FILL_DATA']) ? $controller_map['MIN_FILL_DATA']: array(),
                'prefix'   => $this->_prefix, 
                'dir'      => $controller_map['path'],
                );
             $content = $this->build_tpl($tpl);
             $save_path = sprintf('%s/controllers/%s', $this->_save_path, $controller_map['path']);
             if(!file_exists($save_path)) {
                 mkdir($save_path, 0755, TRUE);
             }
             $save_file = sprintf('%s/%s.php', $save_path, $controller_map ['controller']);
             $fiel_res = file_put_contents($save_file, $content);
             if($fiel_res) {
                 echo  "successfully create: ".$save_file."\n" ;
             } else {
                 echo  "error create: ".$save_file."\n" ;
             }
         }
     }
     function gen_logics_data($tpl){
         global $eh_basedb, $g_dao_map, $g_filter_tables;
         foreach ($g_dao_map[$this->_module] as $table=>$dao_map){
             if (in_array($table, $g_filter_tables)) continue;
             if ($this->_table !='' && $table!= $this->_table) continue;
             $this->_tpl_data = array(
                     'NAME'     => $dao_map ['dao'], 
                     'PREFIX'   => $this->_prefix, 
                     'MODEL'    => $dao_map['model'],
                     'dir' => $dao_map['path'],
                     'foriegn_key' => $dao_map['foriegn_key'],
                     'fetch_name_columns' => isset($dao_map['fetch_name_columns']) ? $dao_map['fetch_name_columns'] : array(),
                     'fetch_name_columns_one' => isset($dao_map['fetch_name_columns_one']) ? $dao_map['fetch_name_columns_one'] : array(),
                     'data_maps_many' => isset($dao_map['data_maps_many']) ? $dao_map['data_maps_many'] : array(),
                     'kv_data_maps_one' => isset($dao_map['kv_data_maps_one']) ? $dao_map['kv_data_maps_one'] : array(),
                     'data_maps_one' => isset($dao_map['data_maps_one']) ? $dao_map['data_maps_one'] : array(),
                     'kv_data_maps_many' => isset($dao_map['kv_data_maps_many']) ? $dao_map['kv_data_maps_many'] : array(),
                     'SEARCH_INTVAL_ITEM'     => isset($dao_map['SEARCH_INTVAL_ITEM']) ? $dao_map['SEARCH_INTVAL_ITEM']: array(),
                     'SEARCH_ITEM'            => isset($dao_map['SEARCH_ITEM']) ? $dao_map['SEARCH_ITEM']: array(),
                     'DATA_MAPS_ONE'          => isset($dao_map['DATA_MAPS_ONE']) ? $dao_map['DATA_MAPS_ONE']: array(),
                     'FETCH_INTERVAL_COLUMNS' => isset($dao_map['FETCH_INTERVAL_COLUMNS']) ? $dao_map['FETCH_INTERVAL_COLUMNS']: array(),
                     'SEARCH_UNION_ITEMS'     => isset($dao_map['SEARCH_UNION_ITEMS']) ? $dao_map['SEARCH_UNION_ITEMS']: array(),
                     );
             $content = $this->build_tpl($tpl);
             $save_path = sprintf('%s/logics/%s', $this->_save_path, $dao_map['path']);
             !file_exists($save_path) ? mkdir($save_path, 0755, TRUE) :'';
             $save_file = sprintf('%s/logic_%s.php', $save_path, $dao_map ['dao']);
             $fiel_res = file_put_contents($save_file, $content);
             if($fiel_res) {
                 echo  "successfully create: ".$save_file."\n" ;
             } else {
                 echo  "error create: ".$save_file."\n" ;
             }
         }

     }
     function gen_models_data($tpl){
         global $eh_basedb, $g_dao_map, $g_filter_tables;
         foreach ($g_dao_map[$this->_module] as $table=>$dao_map){
             if (in_array($table, $g_filter_tables)) continue;
             if ($this->_table !='' && $table!= $this->_table) continue;
             $index_fields  = get_index_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb);
             $fields  = fields_types(array('table'=> $table, 'database'=>$this->_db), $eh_basedb);
             $this->_tpl_data = array(
                     'name'        =>  $dao_map ['dao'], 
                     'prefix'      => $this->_prefix, 
                     'dir'         => $dao_map['path'],
                     'foriegn_key' => $dao_map['foriegn_key'],
                     'has_one'     => isset($dao_map['has_one']) ? $dao_map['has_one'] : '',
                     'has_many'    => isset($dao_map['has_many']) ? $dao_map['has_many'] : '',
                     'mkeys'       => $dao_map['foriegn_key'] != '' ? 
                             array_merge($this->_mkeys($index_fields, $fields), array($dao_map['foriegn_key']. ':%d'=>$dao_map['foriegn_key'])):
                                   $this->_mkeys($index_fields, $fields),
                     'equal_search_items' => $this->_equal_search_items($index_fields),
                     );
             $content = $this->build_tpl($tpl);
             $save_path = sprintf('%s/models/%s', $this->_save_path, $dao_map['path']);
             !file_exists($save_path) ? mkdir($save_path, 0755, TRUE) :'';
             $save_file = sprintf('%s/model_%s.php', $save_path, $dao_map ['dao']);
             $fiel_res = file_put_contents($save_file, $content);
             if($fiel_res) {
                 echo  "successfully create: ".$save_file."\n" ;
             } else {
                 echo  "error create: ".$save_file."\n" ;
             }
         }

     }
     function _equal_search_items($index_fields){
         $search_items = array();
         foreach ($index_fields as $index_field){
             foreach($index_field as $v){
                 $search_items[$v] = $v;
             }
         }
         return  $search_items;
     }
     function _mkeys($index_fields, $fields) {
         $mkeys = array();
         foreach($index_fields as $index_field){
             $keys = array();
             foreach($index_field as  $v){
                 if ( substr($v, -3, 3) == '_at') continue;
                 $keys[$v] = sprintf('%s:%s', $v, $fields[$v]['format']);
             }
             $key = implode('$', $keys);
             if (!empty($keys)){
                 $mkeys[$key] = array_keys($keys);
             }
         }
         return $mkeys;
     }

	 /**
	  * 生成dao模版文件
	  */
     function  gen_daos_data($tpl){
         global $eh_basedb, $g_dao_map, $g_filter_tables;
         foreach ($g_dao_map[$this->_module] as $table=>$dao_map){
             if (in_array($table, $g_filter_tables)) continue;
             if ($this->_table !='' && $table!= $this->_table) continue;
			 $ret = insert_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb);
			 $this->_tpl_data = array(
                     'name'=>$dao_map ['dao'], 
                     'insert_fields' => insert_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb), 
                     'select_fields' => select_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb), 
                     'update_fields' => update_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb), 
                     'prefix'        => $this->_prefix, 
                     'primary_fields'=> get_primary_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb),
                     'unique_fields' => get_unique_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb),
                     'index_fields'   =>get_index_fields(array('table'=> $table, 'database'=>$this->_db), $eh_basedb),
                     'equal_search_items' => $dao_map['equal_search_items'],
                     'like2_search_items' => $dao_map['like2_search_items'],
                     'like_search_items'  => $dao_map['like_search_items'],
                     );

             $content = $this->build_tpl($tpl);
             $save_path = sprintf('%s/daos/%s', $this->_save_path, $dao_map['path']);
             !file_exists($save_path) ? mkdir($save_path, 0755, TRUE) :'';
             $save_file = sprintf('%s/dao_%s.php', $save_path, $dao_map ['dao']);
             $fiel_res = file_put_contents($save_file, $content);
             if($fiel_res) {
                 echo  "successfully create: ".$save_file."\n" ;
             } else {
                 echo  "error create: ".$save_file."\n" ;
             }
         }

     }
	 /**
	  *
	  */
     function build_tpl($tpl){
         foreach ($this->_tpl_data as $k=>$v){
             $this->_smarty->assign($k, $v);

         }
         return  $this->_smarty->fetch($tpl);
     }
     static function usage(){
         $version = '1.0';
         echo <<<EOF
             generator - 自动化部署工具 ($version)
             用法
             {$_SERVER['argv'][0]} [options] [-e|--environment] <environment> [-m|--module] <module>

         选项说明
             --environment=production|testing
             -e <environment> 用于指定要部署的环境，测试环境(testing)生产环境(production)
             --module=fe|rd|thirdsrc
             -m <module> 用于要部署的模块
             -v <version> 用户制定要部署的模块的版本
             --version=模块的版本
             -p <path> 用于要部署的模块
             --path=模块的版本
             -h|--help    显示帮助信息\n
EOF;
         exit(0);
     }
 }
class XSUtil{
	private static $optind, $options = null;
	private static $charset = null;

	/**
	 * 修正字符串至固定宽度
	 * 其中一个全角符号、汉字的宽度为半角字符的 2 倍。
	 * @param string $text 要修正的字符串
	 * @param int $size 修正的目标宽度
	 * @param string $pad 用于填充补足的字符
	 * @return type 
	 */
	public static function fixWidth($text, $size, $pad = ' ')
	{
		for ($i = $j = 0; $i < strlen($text) && $j < $size; $i++, $j++)
		{
			if ((ord($text[$i]) & 0xe0) == 0xe0)
			{
				if (($size - $j) == 1)
					break;
				$j++;
				$i += 2;
			}
		}
		return substr($text, 0, $i) . str_repeat($pad, $size - $j);
	}

	/**
	 * 设置输出、输入编码
	 * 默认输出的中文编码均为 UTF-8
	 * @param string $charset 期望得到的字符集
	 */
	public static function setCharset($charset)
	{
		if ($charset !== null && strcasecmp($charset, 'utf8') && strcasecmp($charset, 'utf-8'))
		{
			self::$charset = $charset;
			ob_start(array(__CLASS__, 'convertOut'));
		}
	}

	/**
	 * 把 UTF-8 字符串转换为用户编码
	 * @param string $buf 要转换字符串
	 * @return string 转换后的字符串
	 */
	public static function convertOut($buf)
	{
		if (self::$charset !== null)
			return XS::convert($buf, self::$charset, 'UTF-8');
		return $buf;
	}

	/**
	 * 把用户输入的字符串转换为 UTF-8 编码
	 * @param string $buf 要转换字符串
	 * @return string 转换后的字符串
	 */
	public static function convertIn($buf)
	{
		if (self::$charset !== null)
			return XS::convert($buf, 'UTF-8', self::$charset);
		return $buf;
	}

	/**
	 * 解析命令行参数
	 * @param array $valued 需要附加值的参数列表
	 * @return array 解析完的参数数组，未指定 - 开头的选项统一放入 '-' 的子数组
	 */
	public static function parseOpt($valued = array())
	{
		$result = array('-' => array());
		$params = isset($_SERVER['argv']) ? $_SERVER['argv'] : array();
		for ($i = 0; $i < count($params); $i++)
		{
			if ($params[$i] === '--')
			{
				for ($i = $i + 1; $i < count($params); $i++)
					$result['-'][] = $params[$i];
				break;
			}
			else if ($params[$i][0] === '-')
			{
				$value = true;
				$pname = substr($params[$i], 1);
				if ($pname[0] === '-')
				{
					$pname = substr($pname, 1);
					if (($pos = strpos($pname, '=')) !== false)
					{
						$value = substr($pname, $pos + 1);
						$pname = substr($pname, 0, $pos);
					}
				}
				else if (strlen($pname) > 1)
				{
					for ($j = 1; $j < strlen($params[$i]); $j++)
					{
						$pname = substr($params[$i], $j, 1);
						if (in_array($pname, $valued))
						{
							$value = substr($params[$i], $j + 1);
							break;
						}
						else if (($j + 1) != strlen($params[$i]))
						{
							$result[$pname] = true;
						}
					}
				}
				if ($value === true && in_array($pname, $valued) && isset($params[$i + 1]))
				{
					$value = $params[$i + 1];
					$i++;
				}
				$result[$pname] = $value;
			}
			else
			{
				$result['-'][] = $params[$i];
			}
		}
		self::$options = $result;
		self::$optind = 1;
		return $result;
	}

	/**
	 * 取得命令行参数
	 * 要求事先调用 parseOpt, 否则会自动以默认参数调用它。
	 * @param string $short 短参数名
	 * @param string $long 长参数名
	 * @param bool $extra 是否补用默认顺序的参数
	 * @return string 返回可用的参数值，若不存在则返回 null
	 * @see parseOpt
	 */
	public static function getOpt($short, $long = null, $extra = false)
	{
		if (self::$options === null)
			self::parseOpt();

		$value = null;
		$options = self::$options;
		if ($long !== null && isset($options[$long]))
			$value = $options[$long];
		else if ($short !== null && isset($options[$short]))
			$value = $options[$short];
		else if ($extra === true && isset($options['-'][self::$optind]))
		{
			$value = $options['-'][self::$optind];
			self::$optind++;
		}
		return $value;
	}

	/**
	 * 刷新标准输出缓冲区
	 */
	public static function flush()
	{
		flush();
		if (ob_get_level() > 0)
			ob_flush();
	}
}
