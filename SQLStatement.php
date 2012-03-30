<?php namespace kohana4\base;

/**
 * @package    Kohana4
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class SQLStatement extends \app\Instantiatable
	implements \kohana4\types\SQLStatement
{
	/**
	 * @var \PDOStatement 
	 */
	protected $statement;
	
	/**
	 * @param \PDOStatement statement
	 * @return \app\SQLStatement
	 * @throws \app\Exception_NotApplicable
	 */
	public static function instance(\PDOStatement $statement = null)
	{
		if ($statement === null)
		{
			throw \app\Exception_NotApplicable::instance('No statement provided.');
		}
		
		$instance = parent::instance();
		$instance->statement($statement);
		return $instance;
	}
	
	/**
	 * @param \PDOStatement statement
	 * @return $this
	 */
	public function statement(\PDOStatement $statement)
	{
		$this->statement = $statement;
	}
	
	/**
	 * @param string parameter
	 * @param string variable
	 * @return $this
	 */
	public function bind($parameter, & $variable)
	{
		$this->statement->bindParam($parameter, $variable, \PDO::PARAM_STR);
		return $this;
	}
	
	/**
	 * @param string parameter
	 * @param int variable
	 * @return $this
	 */
	public function bindInt($parameter, & $variable)
	{
		$this->statement->bindParam($parameter, $variable, \PDO::PARAM_INT);
		return $this;
	}
	
	/**
	 * Stored procedure argument.
	 * 
	 * @param string parameter
	 * @param string variable
	 * @return $this
	 */
	public function bindArg($parameter, & $variable)
	{
		$this->statement->bindParam
			(
				$parameter, 
				$variable, 
				\PDO::PARAM_STR|\PDO::PARAM_INPUT_OUTPUT
			);
		
		return $this;
	}
	
	/**
	 * Execute the statement.
	 * 
	 * @return $this
	 */
	public function execute()
	{
		$this->statement->execute();
		return $this;
	}
	
	/**
	 * Featch as object.
	 * 
	 * @param string class
	 * @param array paramters to be passed to constructor
	 * @return mixed
	 */
	public function fetch_object($class = 'stdClass', array $args = null)
	{
		return $this->statement->fetchObject($class, $args);
	}
	
	/**
	 * Fetch associative array of row.
	 */
	public function fetch_array()
	{
		return $this->statement->fetch(\PDO::FETCH_ASSOC);
	}
	
	/**
	 * Retrieves all remaining rows. Rows are retrieved as arrays.
	 * 
	 * [!!] May be extremely memory intensive when used on large data sets.
	 */
	public function fetch_all()
	{
		return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
	}
	
} # class
