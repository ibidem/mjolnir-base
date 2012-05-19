<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Model
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_Instantiatable extends \app\Instantiatable
	implements \ibidem\types\Params
{
	/**
	 * Target model.
	 * 
	 * @var string 
	 */
	const model = null;
	
	/**
	 * @var int 
	 */
	protected $id;
	
	/**
	 * @var array database row
	 */
	private $current;
	
	/**
	 * @return \ibidem\access\Model_HTTP_User
	 */
	public static function instance($id = null)
	{
		$instance = parent::instance();

		if ($id !== null)
		{
			$instance->instantiate($id);
		}
		
		return $instance;
	}
	
	/**
	 * @param array info
	 * @return \ibidem\base\Model_Instantiatable $this
	 */
	protected function set_current(array $info)
	{
		$this->current = $info;
		return $this;
	}
	
	/**
	 * @param int id
	 */
	protected function instantiate($id)
	{
		$this->id = $id;
		$model_class = '\app\Model_DB_'.static::model;
		$this->current = \app\SQL::prepare
			(
				__METHOD__,
				'
					SELECT *
					  FROM `'.$model_class::table().'` target_table
					 WHERE target_table.id = :id
					 LIMIT 1
				'
			)
			->bind_int(':id', $id)
			->execute()
			->fetch_array();
		
		return $this;
	}
	
	/**
	 * On first call the database row is retrieved and data populated.
	 * 
	 * @return mixed parameter or default 
	 */
	public function get($key, $default = null)
	{
		return isset($this->current[$key]) ? $this->current[$key] : $default;
	}
	
	/**
	 * The method will overwrite the values retrieved from the database. New 
	 * values may also be created (such as various aliases, computed values, 
	 * etc)
	 * 
	 * @param string key
	 * @param mixed value
	 * @return \ibidem\access\Model_HTTP_User $this
	 */
	public function set($key, $value)
	{
		$this->current[$key] = $value;
		return $this;
	}
	
	/**
	 * The method will overwrite the value. New values may also be created.
	 * 
	 * @param array associative array of key values
	 * @return \ibidem\access\Model_HTTP_User $this
	 */
	public function populate_params(array $params)
	{
		foreach ($params as $key => $value)
		{
			$this->current[$key] = $value;
		}
		
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function to_array()
	{
		return $this->current;
	}

} # class
