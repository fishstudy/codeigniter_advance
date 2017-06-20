<?php
/**
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Cache_redis extends CI_Driver
{
	/**
	 * Default config
	 *
	 * @static
	 * @var	array
	 */
	protected static $_default_config = array();
	/**
	 * Redis connection
	 *
	 * @var	Redis
	 */
	protected $_redis;
	/**
	 * An internal cache for storing keys of serialized values.
	 *
	 * @var	array
	 */
	protected $_serialized = array();
	
	public function __construct($params = array()) {
	}
	
	// ------------------------------------------------------------------------
	/**
	 * Get cache
	 *
	 * @param	string	Cache ID
	 * @return	mixed
	 */
	public function get($key)
	{
		$value = $this->_redis->get($key);
		if ($value !== FALSE && isset($this->_serialized[$key]))
		{
			return unserialize($value);
		}
		return $value;
	}
// ----------------sting-------------------   操作
	/**
	 * Get cache
	 *
	 * @param	array()	Cache ID
	 * @return	mixed
	 */
	public function mget($keys)
	{
		$values = $this->_redis->mget($keys);
		if(!empty($values)){
    		foreach($values as $key=>$val) {
    			$values[$key] = unserialize($val); 
    		}
		} else {
		    $values = array();
		}
		return $values;
	}
	
    /**
	 * Get cache
	 *
	 * @param	array()	Cache ID
	 * @return	mixed
	 */
	public function mset($key_vals) {
		if(!empty($key_vals)){
			foreach($key_vals as $key=>$val) {
				$key_vals[$key] = serialize($val);
			}
		}
		$res = $this->_redis->mset($key_vals);
		return $res;
	}
	
	/**
	 * Get cache
	 *
	 * @param	array()	Cache ID
	 * @return	mixed
	 */
	public function set($key, $val) {
	    $res = $this->_redis->set($key, $val);
	    return $res;
	}
	// ------------------------------------------------------------------------
	/**
	 * Save cache
	 *
	 * @param	string	$id	Cache ID
	 * @param	mixed	$data	Data to save
	 * @param	int	$ttl	Time to live in seconds
	 * @param	bool	$raw	Whether to store the raw value (unused)
	 * @return	bool	TRUE on success, FALSE on failure
	 */
	public function save($id, $data, $ttl = 60, $raw = FALSE)
	{
		if (is_array($data) OR is_object($data))
		{
			if ( ! $this->_redis->sAdd('_ci_redis_serialized', $id))
			{
				return FALSE;
			}
			isset($this->_serialized[$id]) OR $this->_serialized[$id] = TRUE;
			$data = serialize($data);
		}
		elseif (isset($this->_serialized[$id]))
		{
			$this->_serialized[$id] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $id);
		}
		return ($ttl)
			? $this->_redis->setex($id, $ttl, $data)
			: $this->_redis->set($id, $data);
	}
	// ------------------------------------------------------------------------
	/**
	 * Delete from cache
	 *
	 * @param	string	Cache key
	 * @return	bool
	 */
	public function del($key)
	{
		if ($this->_redis->delete($key) !== 1)
		{
			return FALSE;
		}
		if (isset($this->_serialized[$key]))
		{
			$this->_serialized[$key] = NULL;
			$this->_redis->sRemove('_ci_redis_serialized', $key);
		}
		return TRUE;
	}
//--------------------hash---------------字典操作

	/**
	 * 可以直接存仓PHP的数组
	 */
	public function hget($key) {
	    return $this->_redis->hget($key);
	}
	
    /**
	 * 可以直接存仓PHP的数组
	 */
	public function hmset($key,$ary) {
	    return $this->_redis->hmset($key,$ary);
	}
	
	/**
	 * 可以直接存仓PHP的数组
	 */
	public function hgset($key,$ary) {
	    return $this->_redis->hmset($key,$ary);
	}
	
	/**
	 * 获取全部内容
	 * @param unknown_type $key
	 */
	public  function hgetall($key) {
	    return $this->_redis->hgetall($key);
	}
	
	
// -----------------list -----------------队列操作
	/**
	 * 保持队列中只有固定的数目
	 * ltrim from cache
	 *
	 * @param   string  Cache key
	 * @return  bool
	 */
	public function ltrim($key, $start,$end=0)
	{
	    $this->_redis->ltrim($key,$start,$end);
	  
	    return TRUE;
	}
	/**
     * Delete from cache
     *
     * @param   string  Cache key
     * @return  bool
     */
    public function lrem($key,$val,$count=0)
    {
        if ($this->_redis->lrem($key,$val,$count) !== 1)
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * 返回队列中总数
     * lsize from cache
     *
     * @param   string  Cache key
     * @return  bool
     */
    public function lsize($key)
    {
        $return = $this->_redis->lsize($key, $start, $end);
        return $return;
    }
    
    /**
     * lrange from cache
     *
     * @param   string  Cache key
     * @return  bool
     */
    public function lrange($key,$start=0, $end=-1)
    {
        $return = $this->_redis->lrange($key, $start, $end);
        return $return;
    }
    
    /**
     * lpush from cache
     *
     * @param   string  Cache key
     * @return  bool
     */
    public function lpush($key,$value)
    {
        $return = $this->_redis->lpush($key, $value);
        if(!empty($return)){
            return TRUE;
        } else {
            return FALSE;
        }
    }

	/**
	 * 如果插入列表是个大数组，分批插入，目前先定100个做一次插入
	 * 先把数组切割成小数组
     * lpush from cache
     *
     * @param   string  Cache key
     * @return  bool
     */
    public function lpush_multi($key, $val_ary)
    {
		if(!is_array($val_ary)) {
			return false;
		}

		$max_per_times = 100;
 		$len = count($insert_ary);
		if($len <=$max_per_times) {
			$this->create_multi_params($key, $val_ary);
		} else {
		    $times = ceil($len/$multinum);
		    for($i=0; $i<$times; $i++) {
		     	$offset = $i*$max_per_times;
		     	$a = array_slice($insert_ary, $offset,$max_per_times);
		     	$this->create_multi_params($key, $a);
		    }
		}
		
        return true;
    }

    /**
     * 给定数组 一次把数组的所有内容都插入队列中
     * @param unknown_type $key
     * @param unknown_type $a
     */
	protected function lpush_array($key, $a) {
	    if(!is_array($a)) {
	        return false;
	    }
		$times = count($a);
		$str = '$key';
		for($i=0; $i<$times; $i++) {
 			$str .= ',$a['.$i.']';
 		}
 		eval("\$res = \$this->_redis->rpush($str);");
	}
	
	/**
	 * 保持队列中只有固定的数目
	 * ltrim from cache
	 *
	 * @param   string  Cache key
	 * @return  bool
	 */
	public function setTimeout($key, $second)
	{
	    $this->_redis->setTimeout($key,$second);
	     
	    return TRUE;
	}
	// ------------------------------------------------------------------------
	/**
	 * Increment a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to add
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function increment($id, $offset = 1)
	{
		return $this->_redis->incr($id, $offset);
	}
	// ------------------------------------------------------------------------
	/**
	 * Decrement a raw value
	 *
	 * @param	string	$id	Cache ID
	 * @param	int	$offset	Step/value to reduce by
	 * @return	mixed	New value on success or FALSE on failure
	 */
	public function decrement($id, $offset = 1)
	{
		return $this->_redis->decr($id, $offset);
	}
	// ------------------------------------------------------------------------
	/**
	 * Clean cache
	 *
	 * @return	bool
	 * @see		Redis::flushDB()
	 */
	public function clean()
	{
		return $this->_redis->flushDB();
	}
	// ------------------------------------------------------------------------
	/**
	 * Get cache driver info
	 *
	 * @param	string	Not supported in Redis.
	 *			Only included in order to offer a
	 *			consistent cache API.
	 * @return	array
	 * @see		Redis::info()
	 */
	public function cache_info($type = NULL)
	{
		return $this->_redis->info();
	}
	// ------------------------------------------------------------------------
	public function error_message()
	{
		return true;
	}
	// ------------------------------------------------------------------------
	/**
	 * Get cache metadata
	 *
	 * @param	string	Cache key
	 * @return	array
	 */
	public function get_metadata($key)
	{
		$value = $this->get($key);
		if ($value)
		{
			return array(
				'expire' => time() + $this->_redis->ttl($key),
				'data' => $value
			);
		}
		return FALSE;
	}
	// ------------------------------------------------------------------------
	/**
	 * Check if Redis driver is supported
	 *
	 * @return	bool
	 */
	public function is_supported()
	{
		if (extension_loaded('redis'))
		{
			return $this->_setup_redis();
		}
		else
		{
			log_message('debug', 'The Redis extension must be loaded to use Redis cache.');
			return FALSE;
		}
	}
	// ------------------------------------------------------------------------
	/**
	 * Setup Redis config and connection
	 *
	 * Loads Redis config file if present. Will halt execution
	 * if a Redis connection can't be established.
	 *
	 * @return	bool
	 * @see		Redis::connect()
	 */
	protected function _setup_redis()
	{
		$config = array();
		$CI =& get_instance();
		if ($CI->config->load('redis', TRUE, TRUE)) {
		    $redis_center = $CI->config->item('redis');
		    $reidis_conf = configcenter4_get_serv($redis_center['redis'][0], 0, 0);
		    log_message('info', 'Cache: Redis  config :'.$reidis_conf);
		    $reids_conf = explode(':',  $reidis_conf);
		    $conf =array(
		                    'socket_type' => 'tcp',
		                    'host'			=> $reids_conf[0],
		                    'port'			=> $reids_conf[1],
		                    'timeout'		=> '0',
		                    );
			$config += $conf;
		} else {
		    log_message('error', 'Cache: Redis  config error');
		}
		
		$config = array_merge(self::$_default_config, $config);
		$this->_redis = new Redis();
		try
		{
			if ($config['socket_type'] === 'unix')
			{
				$success = $this->_redis->connect($config['socket']);
			}
			else // tcp socket
			{
				$success = $this->_redis->connect($config['host'], $config['port'], $config['timeout']);
			}
			if ( ! $success)
			{
				log_message('debug', 'Cache: Redis connection refused. Check the config.');
				return FALSE;
			}
		}
		catch (RedisException $e)
		{
			log_message('debug', 'Cache: Redis connection refused ('.$e->getMessage().')');
			return FALSE;
		}
		if (isset($config['password']))
		{
			$this->_redis->auth($config['password']);
		}
		// Initialize the index of serialized values.
		$serialized = $this->_redis->sMembers('_ci_redis_serialized');
		if ( ! empty($serialized))
		{
			$this->_serialized = array_flip($serialized);
		}
		return TRUE;
	}
	// ------------------------------------------------------------------------
	/**
	 * Class destructor
	 *
	 * Closes the connection to Redis if present.
	 *
	 * @return	void
	 */
	public function __destruct()
	{
		if ($this->_redis)
		{
			$this->_redis->close();
		}
	}
}
/* End of file Cache_redis.php */
/* Location: ./system/libraries/Cache/drivers/Cache_redis.php */