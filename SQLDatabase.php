<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2008-2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class SQLDatabase extends \app\Instantiatable
	implements \ibidem\types\SQLDatabase
{
	/**
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * @var int
	 */
	protected $savepoint = 0;
	
	/**
	 * @var string 
	 */
	protected $dialect_default;
	
	/**
	 * @var string 
	 */
	protected $dialect_target;
	
	/**
	 * @var \PDO
	 */
	protected $dbh;
	
	/**
	 * @return \app\SQL
	 */
	public static function instance($database = 'default')
	{
		if ( ! isset(static::$instances[$database]))
		{
			static::$instances[$database] = parent::instance();
			try 
			{
				// attempt to load configuration
				$pdo = \app\CFS::config('ibidem/database');
				$pdo = $pdo[$database];
				if (empty($pdo))
				{
					throw \app\Exception_NotFound::instance
						('Missing database configuration.')
						->set_title('Database Error');
				}
				// setup database handle
				$dbh = static::$instances[$database]->dbh = new \PDO
					(
						$pdo['connection']['dsn'], 
						$pdo['connection']['username'], 
						$pdo['connection']['password']
					);
				// set error mode
				$dbh->setAttribute
					(
						\PDO::ATTR_ERRMODE, 
						\PDO::ERRMODE_EXCEPTION 
					);
				// default SQL flavor
				static::$instances[$database]->dialect_default = $pdo['dialect_default'];
				static::$instances[$database]->dialect_target = $pdo['dialect_target'];
				
				return static::$instances[$database];
			}
			catch (\PDOException $e)
			{
				throw \app\Exception::instance($e->getMessage())
					->set_title('Database Error');
			}
		}
		else # is set
		{
			return static::$instances[$database];
		}
	}
	
	/**
	 * Cleanup
	 */	
	public function __destruct()
	{
		$this->dbh = null;
	}
	
	/**
	 * @param string statement
	 * @param string lang
	 * @return boolean requires translation?
	 */
	protected function requires_translation($statement, $lang)
	{
		return ($lang && $lang !== $this->dialect_target) 
			|| ($this->dialect_default !== $this->dialect_target)
			|| $statement === null;
	}
	
	/**
	 * @param string key
	 * @return \ibidem\types\SQLStatement
	 */
	protected function run_stored_statement($key)
	{
		$splitter = \strpos($key, \ibidem\types\SQLDatabase::KEYSPLIT);
		$file = \substr($key, 0, $splitter);
		$key = \substr($key, $splitter+1);
		$statements = \app\CFS::config('sql/'.$this->dialect_target.'/'.$file);
		if ( ! isset($statements[$key]))
		{
			$file = \ibidem\cfs\CFSCompatible::CNFDIR
				. '/sql/'.$this->dialect_target.'/'.$file;
			
			throw \app\Exception_NotFound::instance
				('Missing key ['.$key.'] in ['.$file.'].')
				->set_title('Database Translation Error');
		}
		return $statements[$key]($this->dbh);
	}
	
	/**
	 * @param string key
	 * @param string statement
	 * @param string language of statement
	 * @return \ibidem\types\SQLStatement
	 */
	public function prepare($key, $statement = null, $lang = null)
	{
		if ($this->requires_translation($statement, $lang))
		{
			return $this->run_stored_statement($key);
		}
		else # translation not required
		{
			$prepared_statement = $this->dbh->prepare($statement.' -- '.$key);
			return \app\SQLStatement::instance($prepared_statement);
		}
	}
	
	/**
	 * Begin transaction.
	 * 
	 * @return $this
	 */
	public function begin()
	{
		if ($this->savepoint == 0)
		{
			$this->dbh->beginTransaction();
		}
		else # we are in a transaction
		{
			$this->statement('transaction:begin', 'SAVEPOINT save'.$this->savepoint, 'mysql');
		}
		++$this->savepoint;
	}
	
	/**
	 * Commit transaction.
	 * 
	 * @return $this
	 */
	public function commit()
	{
		--SQL::$savepoint;
		if (SQL::$savepoint == 0)
		{
			$this->dbh->commit();
		}
		else # we are still in another transaction
		{
			$this->statement('transaction:commit', 'RELEASE SAVEPOINT save'.$this->savepoint, 'mysql');
		}
	}
	
	/**
	 * Rollback transaction.
	 * 
	 * @return $this
	 */
	public function rollback()
	{
		--SQL::$savepoint;
		if (SQL::$savepoint == 0)
		{
			$this->dbh->rollBack();
		}
		else # we are still in another transaction
		{
			$this->statement('transaction:rollback', 'ROLLBACK TO SAVEPOINT save'.SQL::$savepoint);
		}
	}
	
} # class
