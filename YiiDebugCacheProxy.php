<?php
/**
 * YiiDebugCacheProxy class file.
 *
 * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
 */

Yii::import('system.caching.CCache');

/**
 * Class YiiDebugCacheProxy
 *
 * Class to profile cache requests
 *
 * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
 * @version $Id$
 * @package
 */
class YiiDebugCacheProxy extends CCache
{
	/**
	 * @var boolean whether to enable profiling requests to cache.
	 * Defaults to false. This should be mainly enabled and used during development
	 * to find out the bottleneck of cache executions.
	 */
	public $enableProfiling = false;

	/**
	 * Options to create cache component
	 * @var array
	 */
	public $cache;

	/**
	 * Cache component instance
	 * @var \CCache
	 */
	protected $_cacheProxy;

	/**
	 * Cache usage statistics
	 * @var array
	 */
	protected $_stats = array(
		'get' => 0, // read requests quantity
		'get_time' => 0, // read time
		'mget' => 0,
		'mget_time' => 0,
		'set' => 0, // write requests quantity
		'add' => 0, // add requests quantity
		'set_time' => 0, // write time
		'delete' => 0, // delete requests quantity
		'delete_time' => 0, // delete time
		'flush' => 0,
		'flush_time' => 0,
		'hit' => 0, // read requests when data found
		'miss' => 0, // read requests when no data found
		'count' => 0, // total requests
		'time' => 0, // total time
	);

	/**
	 * Key for profiling logs
	 * @var
	 */
	private $_logCategory;

	const LOG_CATEGORY = 'system.caching.';

	public function init()
	{
		parent::init();
		$this->_logCategory = self::LOG_CATEGORY.get_class($this);
	}

	/**
	 * @return CCache
	 */
	protected function getCacheProxy()
	{
		if (is_null($this->_cacheProxy))
		{
			$this->_cacheProxy = Yii::createComponent($this->cache);
			$this->_cacheProxy->init();
		}
		return $this->_cacheProxy;
	}

	/**
	 * @see \CCache::get
	 * @param string $id
	 * @return mixed
	 */
	public function get($id)
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.get');
		}
		$value = $this->getCacheProxy()->get($id);
		if ($this->enableProfiling)
		{
			++$this->_stats['get'];
			++$this->_stats['hit'];
			if (false==$value)
				++$this->_stats['miss'];
			Yii::endProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.get');
		}
		return $value;
	}

	/**
	 * @see \CCache::mget
	 * @param string[] $ids
	 * @return array
	 */
	public function mget($ids)
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'("'.implode('","', $ids).'")', $this->_logCategory.'.mget');
		}
		$value = $this->getCacheProxy()->mget($ids);
		if ($this->enableProfiling)
		{
			++$this->_stats['mget'];
			++$this->_stats['hit'];
			if (empty($value))
			{
				++$this->_stats['miss'];
			}
			Yii::endProfile(__METHOD__.'("'.implode('","', $ids).'")', $this->_logCategory.'.mget');
		}
		return $value;
	}

	/**
	 * @see \CCache::set
	 * @param string $id
	 * @param mixed $value
	 * @param int $expire
	 * @param null $dependency
	 * @return bool|mixed
	 */
	public function set($id, $value, $expire = 0, $dependency = null)
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.set');
		}
		$returnValue = $this->getCacheProxy()->set($id, $value, $expire, $dependency);
		if ($this->enableProfiling)
		{
			++$this->_stats['set'];
			Yii::endProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.set');
		}
		return $returnValue;
	}

	/**
	 * Stores a value identified by a key into cache if the cache does not contain this key.
	 * Nothing will be done if the cache already contains the key.
	 * @param string $id the key identifying the value to be cached
	 * @param mixed $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @param ICacheDependency $dependency dependency of the cached item. If the dependency changes, the item is labeled invalid.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	public function add($id,$value,$expire=0,$dependency=null)
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.add');
		}

		$returnValue = $this->getCacheProxy()->add($id, $value, $expire, $dependency);

		if ($this->enableProfiling)
		{
			++$this->_stats['add'];
			Yii::endProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.add');
		}
		return $returnValue;
	}
	/**
	 * @see \CCache::delete
	 * @param string $id
	 * @return bool
	 */
	public function delete($id)
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.delete');
		}
		$value = $this->getCacheProxy()->delete($id);
		if ($this->enableProfiling)
		{
			++$this->_stats['delete'];
			Yii::endProfile(__METHOD__.'("'.$id.'")', $this->_logCategory.'.delete');
		}
		return $value;
	}

	/**
	 * @see \CCache::flush
	 * @return bool
	 */
	public function flush()
	{
		if ($this->enableProfiling)
		{
			Yii::beginProfile(__METHOD__.'()', $this->_logCategory.'.flush');
		}
		$value = $this->getCacheProxy()->flush();
		if ($this->enableProfiling)
		{
			++$this->_stats['flush'];
			Yii::endProfile(__METHOD__.'()', $this->_logCategory.'.flush');
		}
		return $value;
	}

	/**
	 * Returns the statistics about cache usage.
	 * @return array
	 */
	public function getStats()
	{
		$logger = Yii::getLogger();
		$this->_stats['get_time'] = array_sum($logger->getProfilingResults(null, $this->_logCategory.'.get'));
		$this->_stats['mget_time'] = array_sum($logger->getProfilingResults(null, $this->_logCategory.'.mget'));
		$this->_stats['set_time'] = array_sum($logger->getProfilingResults(null, $this->_logCategory.'.set'));
		$this->_stats['delete_time'] = array_sum($logger->getProfilingResults(null, $this->_logCategory.'.delete'));
		$this->_stats['flush_time'] = array_sum($logger->getProfilingResults(null, $this->_logCategory.'.flush'));
		$this->_stats['call'] = $this->_stats['get'] + $this->_stats['mget'] + $this->_stats['set'] + $this->_stats['delete'] + $this->_stats['flush'];
		$this->_stats['time'] = $this->_stats['get_time'] + $this->_stats['mget_time'] + $this->_stats['set_time'] + $this->_stats['delete_time'] + $this->_stats['flush_time'];
		return $this->_stats;
	}
}
