<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_SQL_Factory extends \app\Model_Factory
{
	/**
	 * @var string
	 */
	protected static $table;
	
	/**
	 * Table for model.
	 * 
	 * @return string 
	 */
	public static function table()
	{
		$database_config = \app\CFS::config('ibidem/database');
		return $database_config['table_prefix'].static::$table;
	}
	
	/**
	 * @param string resource id 
	 * @param array config
	 */
	public static function dependencies($id, array $config = null)
	{
		if ($config && isset($config['dependencies']))
		{
			foreach ($config['dependencies'] as $dependency)
			{
				$dependency::inject($id);
			}
		}
	}

} # class