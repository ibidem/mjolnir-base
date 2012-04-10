<?php namespace ibidem\base;

/** 
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_TaskRunner extends \app\Layer 
{
	/**
	 * @var string
	 */
	protected static $layer_name = \ibidem\types\Layer::DEFAULT_LAYER_NAME;
	
	/**
	 * @var array
	 */
	protected static $helpcommands = array
		(
			'help', '--help', '-h', '-?', '-help'
		);
	
	/**
	 * @var array
	 */
	protected static $command_struct = array
		(
			'description' => array(),
			'flags' => array(),
		);
	
	/**
	 * @var array
	 */
	protected static $flag_struct = array
		(
			'description' => 'Description not avaiable.',
			'default' => NULL,
			'type' => 'toggle',
			'short' => NULL,
		);
	
	/**
	 * @var string
	 */
	protected static $commandname = 'minion';
	
	/**
	 * @var \ibidem\types\Writer
	 */
	protected $writer;
	
	/**
	 * @var int
	 */
	protected $argc;
	
	/**
	 * @var array
	 */
	protected $argv;
	
	/**
	 * @param array args
	 */
	public function args(array $args)
	{
		$this->argv = $args;
		$this->argc = \count($args);
		return $this;
	}
	
	/**
	 * @param string command name
	 */
	public function commandname($commandname)
	{
		$this->commandname = $commandname;
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Writer writer
	 */
	public function writer($writer)
	{
		$this->writer = $writer;
		return $this;
	}
	
	/**
	 * @param \ibidem\types\Layer layer
	 * @param \ibidem\types\Layer parent
	 * @return \ibidem\base\Layer_TaskRunner $this
	 */
	public function register(\ibidem\types\Layer $layer)
	{
		// Note: In this implementation we treat MVC as a self contained pattern
		// for the sake of purity of the pattern so we don't support sub layers.
		throw new \app\Exception_NotApplicable
			(
				__CLASS__.' does not support sublayer.'
			);
	}
	
	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try
		{
			if ( ! $this->writer)
			{
				$this->writer = \app\Writer_Console::instance();
			}
			// ensure command line arguments
			if ( ! $this->argc || ! $this->argv)
			{
				$this->argc = $_SERVER['argc'];
				$this->argv = $_SERVER['argv'];
			}
			// ensure new line after command
			$this->writer->eol();
			
			// got paramters?
			if ($this->argc === 1)
			{
				$this->helptext(); # default to help on no command
				exit;
			}

			// load configuration
			$tasks = \app\CFS::config('ibidem/tasks');
			// get command
			$command = \strtolower($this->argv[1]);
			// help command? (handle internally)
			if (\in_array($command, static::$helpcommands)) 
			{
				if (isset($this->argv[2])) # specific help topic
				{
					$this->commandhelp($this->argv[2]);
					exit;
				}
				else # general help
				{
					$this->helptext();
					exit;
				}
			}
			// valid command?
			if ( ! isset($tasks[$command]))
			{
				$this->writer->error('Invalid command: '.$command)->eol();
				$this->writer->status('Help', 'For more help type: php '.static::$commandname.' help')->eol();
				exit(1);
			}
			// check for help flag (-h or --help)
			for ($i = 2; $i < $this->argc; ++$i)
			{
				if ($this->argv[$i] === '--help' || $this->argv[$i] === '-h')
				{
					$this->commandhelp($command);
					exit;
				}
			}
			// normalize command
			$tasks[$command] = static::normalize($tasks[$command]);
			// initialize configuration
			$config = array();
			foreach ($tasks[$command]['flags'] as $flag => $flaginfo)
			{
				$config[$flag] = $flaginfo['default'];
			}
			// check flags
			$flags = \array_keys($tasks[$command]['flags']);
			foreach ($flags as $flagkey)
			{
				if ( ! isset($tasks[$command]['flags'][$flagkey]))
				{
					$this->writer->error('Invalid configuration');
				}

				$flag = $tasks[$command]['flags'][$flagkey];
				for ($i = 2; $i < $this->argc; ++$i)
				{
					if ($this->argv[$i] === '--'.$flagkey || $this->argv[$i] === '-'.$flag['short'])
					{
						if ($flag['type'] === 'toggle')
						{
							// toggle is a special flag type; always false by
							// default, switches to true on use
							$config[$flagkey] = true;
						}
						else # non-toggle type
						{
							$config[$flagkey] = \call_user_func($flag['type'], $i, $this->argv);
						}
					}
				}
			}

			// check for missing flags
			$missing_flags = array();
			foreach ($config as $flag => $value)
			{
				if ($value === null) 
				{
					$missing_flags[] = $flag;
				}
			}
			
			// handle missing flags
			if ( ! empty($missing_flags))
			{
				$this->writer
					->error('Missing required flags. Command terminated.')->eol()->eol()
					->status('Help', 'For help type: '.static::$commandname.' '.$command.' -h', \ibidem\types\Enum_Color::Yellow)->eol()
					->eol()
					->subheader('Missing Flags');
				$this->render_flags($tasks[$command], $missing_flags);
				$this->writer->eol();
				exit(1);
			}
			
			// run the task
			\app\Task::instance($command)
				->writer($this->writer)
				->config($config)
				->execute();

			// ensure new line after execution
			$this->writer->eol();
		}
		catch (\Exception $e)
		{
			$this->exception($e, true);
		}
	}
	
	/**
	 * Fills body and approprite calls for current layer, and passes the 
	 * exception up to be processed by the layer above, if the layer has a 
	 * parent.
	 * 
	 * @param \Exception
	 * @param boolean layer is origin of exception?
	 */
	public function exception(\Exception $exception, $origin = false)
	{
		if (\is_a($exception, '\ibidem\types\Exception'))
		{
			$this->writer->error($exception->message())->eol();
		}
		
		parent::exception($exception, $origin);
	}
	
	/**
	 * General help information.
	 */
	public function helptext()
	{	
		$stdout = $this->writer;
		$v = \app\CFS::config('version');
		$v = $v['overlord'];
		$version = $v['major'].'.'.$v['minor']
			. ($v['hotfix'] !== '0' ? '.'.$v['hotfix'] : '')
			. ( ! empty($v['tag']) ? '-'.$v['tag'] : '');
		$stdout->header('overlord v'.$version);
		$stdout->write("    USAGE: ".static::$commandname." [command] [flags]")->eol();
		$stdout->write("       eg. ".static::$commandname." example:cmd -i Joe --greeting \"Greetings, Mr.\" --date")->eol()->eol();
		$stdout->write("     Help: ".static::$commandname." [command] -h")->eol()->eol()->eol();
		$stdout->subheader('Commands');
		// load config
		$cli = \app\CFS::config('ibidem/tasks');
		// normalize
		foreach ($cli as $command => $commandinfo)
		{
			$cli[$command] = static::normalize($commandinfo);
		}
		// sort commands
		# \ksort($cli);
		// determine max length
		$max_command_length = 4;
		foreach (\array_keys($cli) as $command)
		{
			if (\strlen($command) > $max_command_length)
			{
				$max_command_length = \strlen($command);
			}
		}
		$command_format = '  %'.$max_command_length.'s  - ';
		// internal help command
		$stdout
			->writef($command_format, 'help')
			->write('Help information. (current command)')
			->eol();
		
		// configuration commands
		foreach ($cli as $command => $info)
		{
			$stdout
				->listwrite
					(
						\sprintf($command_format, $command),
						$info['description'][0],
						$max_command_length + 6
					)
				->eol();
		}
		
		// terminate after displaying help
		$stdout->eol();
	}	
	
	/**
	 * @param string command
	 */
	protected function commandhelp($commandname)
	{	
		$stdout = $this->writer;
		$stdout->header('Help for '.$commandname);
		// configuration
		$cli = \app\CFS::config('ibidem/tasks');
		// normalize
		$command = static::normalize($cli[$commandname]);

		// display quick syntax help
		$helptext_head = ' '.static::$commandname.' '.$commandname;
		$helptext_head_length = \strlen($helptext_head) + 1;
		$helptext = '';
		foreach ($command['flags'] as $flag => $flaginfo)
		{
			if ($flaginfo['default'] === null) # mandatory paramter
			{
				$helptext .= ' --'.$flag;
				if ($command['flags'][$flag]['type'] !== 'toggle')
				{
					// the & is a placeholder for space to symbolize we don't 
					// want a break; see ->listwrite later
					$helptext .= '&<'.\preg_replace('#^[^:]*::#', '', $command['flags'][$flag]['type']).'>';
				}
			}
			else # optional paramter
			{
				$helptext .= ' [--'.$flag;
				if ($command['flags'][$flag]['type'] !== 'toggle')
				{
					$helptext .= '&<'.\preg_replace('#^[^:]*::#', '', $command['flags'][$flag]['type']).'>';
				}
				$helptext .= ']';
			}
		}
		
		$stdout
			->listwrite($helptext_head, $helptext, $helptext_head_length, '&')
			->eol()->eol();
		
		// display description
		foreach ($command['description'] as $description)
		{
			$stdout
				->write($description, 4)
				->eol()->eol();
		}
		// display detailed flag information
		$stdout->eol()->subheader('Flags');
		if (\count($command['flags']) === 0)
		{
			$stdout->write("    This command does not accept any flags.");
		}
		else # has flags
		{
			$stdout->write(' '.static::$commandname.' '.$commandname)->eol();
			$this->render_flags($command, null);
			$stdout->eol();
			$stdout->eol()->subheader('Default Values');
			$count = $this->render_flags($command, null, 'default');
			if (empty($count))
			{
				$stdout->write('    All flags are required.');
			}
		}
		
		// terminate after displaying help
		$stdout->eol();
	}
	
	/**
	 * Gurantees the default structure is set for the command and it's flags.
	 * 
	 * @param array command
	 * @return array
	 */
	protected static function normalize($command)
	{
		$command = \app\CFS::merge(static::$command_struct, $command);
		if (empty($command['description']))
		{
			$command['description'] = array('No description available at this time.');
		}
		$normalizedflags = array();
		foreach ($command['flags'] as $flag => $flaginfo)
		{
			$normalizedflags[$flag] = \app\CFS::merge(static::$flag_struct, $flaginfo);
			// gurantee toggles are boolean
			if ($normalizedflags[$flag]['type'] === 'toggle' && $normalizedflags[$flag]['default'] === null)
			{
				$normalizedflags[$flag]['default'] = false;
			}
		}
		
		// re-arranging; sort functions would achive the same result but may not
		// maintain the configuration order -- which may help in understanding
		// the command's flags
		$sortedflags = array();
		// required flags
		foreach ($normalizedflags as $key => $flag)
		{
			if ($flag['type'] !== 'toggle' && $flag['default'] === null)
			{
				$sortedflags[$key] = $flag;
			}
		}
		// optional flags
		foreach ($normalizedflags as $key => $flag)
		{
			if ($flag['type'] !== 'toggle' && $flag['default'] !== null)
			{
				$sortedflags[$key] = $flag;
			}
		}
		// toggle's (automatically optional flags)
		foreach ($normalizedflags as $key => $flag)
		{
			if ($flag['type'] === 'toggle')
			{
				$sortedflags[$key] = $flag;
			}
		}		
		$command['flags'] = $sortedflags;
		
		return $command;
	}
	
	/**
	 * @param array command
	 * @param array flagkeys
	 * @param string description key
	 * @return int 
	 */
	protected function render_flags
	(
		$command, 
		$flagkeys = null, 
		$descriptionkey = 'description'
	)
	{
		if ($flagkeys === null)
		{
			$flagkeys = \array_keys($command['flags']);
		}
		
		// detect maximum flag length
		$max_flag_length = 0;
		foreach ($flagkeys as $flag)
		{
			$length = \strlen($flag);
			if ($command['flags'][$flag]['type'] !== 'toggle')
			{
				$clean_type = \preg_replace('#^[^:]*::#', '', $command['flags'][$flag]['type']);
				$length += \strlen($clean_type) + 5;
			}
			if ($length > $max_flag_length)
			{
				$max_flag_length = $length;
			}
		}
		$displaycount = 0;
		$format_dt = ' %4s %-'.$max_flag_length.'s  - ';
		foreach ($flagkeys as $flag)
		{
			$flaginfo = $command['flags'][$flag];
			// only display flags with description data
			if ($flaginfo[$descriptionkey] !== null)
			{
				$clean_type = \preg_replace('#^[^:]*::#', '', $flaginfo['type']);
				$type = $clean_type === 'toggle' ? '' : '<'.$clean_type.'>';
				$short = $flaginfo['short'] === null ? '' : '-'.$flaginfo['short'];
				if (\is_bool($flaginfo[$descriptionkey]))
				{
					$description = $flaginfo[$descriptionkey] ? 'on' : 'off';
				}
				else # not boolean
				{
					$description = $flaginfo[$descriptionkey];
				}
				$this->writer
					->listwrite
						(
							\sprintf($format_dt, $short, '--'.$flag.' '.$type), 
							$description,
							$max_flag_length + 10
						)
					->eol();
				
				++$displaycount;
			}
		}
		
		return $displaycount;
	}
	
} # class
