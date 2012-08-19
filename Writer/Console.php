<?php namespace ibidem\base;

/**
 * @package    ibidem
 * @category   Base
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Writer_Console extends \app\Instantiatable 
	implements \ibidem\types\Writer
{
	/**
	 * @var boolean
	 */
	protected $highlight_support = true;
	
	/**
	 * @var int
	 */
	protected $width;
	
	/**
	 * @var array
	 */
	protected $highlight;
	
	/**
	 * @var resource output stream
	 */
	protected $stdout;
	
	/**
	 * @var resource error stream
	 */
	protected $stderr;
	
	/**
	 * @var string
	 */
	protected $shell;
	
	/**
	 * @return \ibidem\base\Writer_Console
	 */
	static function instance()
	{
		$instance = parent::instance();
		$config = \app\CFS::config('ibidem/writer/console');
		$instance->highlight = $config['highlight'];
		$instance->width = $config['width'];

		if ($config['highlighting'])
		{
			// -e means "interpret ANSI codes"; if the console echo doesn't know of 
			// it then what will happen is we'll get "-e test" back -- note the way
			// php works is it will test the system console not the one you're on,
			// so on windows for example you can run git bash but php still uses cmd
			if (\trim(\shell_exec('echo -e test')) !== 'test')
			{
				// bash exists? (eg. git comes with it)
				if (\trim(\shell_exec('bash echo -ne test')) !== 'test')
				{
					$instance->highlight_support = false;
				}
				else # bash exists on system
				{

					$instance->shell = 'bash echo -ne "%s"';
				}
			}
		}
		else # no highlighting
		{
			$instance->highlight_support = false;
		}
		
		$instance->stdout = \fopen('php://stdout', 'w');
		$instance->stderr = \fopen('php://stderr', 'w');
		
		return $instance;
	}
	
	/**
	 * @param int width
	 * @return \ibidem\base\Writer_Console $this
	 */
	function width($width)
	{
		$this->width = $width;
		return $this;
	}
	
	/**
	 * @return \ibidem\base\Writer_Console $this
	 */
	function eol()
	{
		\fprintf($this->stdout, PHP_EOL);
		return $this;
	}
	
	/**
	 * @return string EOL as string
	 */
	function eol_string()
	{
		return PHP_EOL;
	}
	
	/**
	 * @param string text
	 * @return \ibidem\base\Writer_Console $this
	 */
	function write($text, $indent = 0, $nowrap_hint = null)
	{
		if ($indent !== 0)
		{
			$indent_text = \str_repeat(' ', $indent);
			$text = \wordwrap
				(
					$text, 
					$this->width - $indent, 
					$this->eol_string().$indent_text
				);	
			$text = $indent_text.$text;
		}
		
		if ($nowrap_hint !== null)
		{
			$text = \str_replace($nowrap_hint, ' ', $text);
		}
		
		\fprintf($this->stdout, '%s', $text);
		return $this;
	}
	
	/**
	 * @param string term
	 * @param string definition
	 * @param int indent hint
	 * @param string no wrap hint string
	 * @return \ibidem\base\Writer_Console $this
	 */
	function listwrite($dt, $dd, $indent_hint = null, $nowrap_hint = null)
	{
		if ( ! $indent_hint)
		{
			$indent_hint = \strlen($dt);
		}
		
		$text = $dt.$dd;
		if (\strlen($text) >= $this->width)
		{
			$firstline = \substr($text, 0, $this->width);
			$otherlines = \substr($text, $this->width);
			if ($otherlines[0] !== ' ')
			{
				// imperfect cut
				$lastspace = \strrpos($firstline, ' ');
				$extra = \substr($firstline, $lastspace);
				$firstline = \substr($firstline, 0, $lastspace);
				$otherlines = $extra.$otherlines;
			}
			$otherlines = \trim($otherlines);
			if ($nowrap_hint)
			{
				$firstline = \str_replace($nowrap_hint, ' ', $firstline);
			}
			$this->write($firstline)->eol();
			$indented_text = \wordwrap
				(
					$otherlines,
					$this->width - $indent_hint,
					PHP_EOL.\str_repeat(' ', $indent_hint)
				);
			if ($nowrap_hint)
			{
				$indented_text = \str_replace($nowrap_hint, ' ', $indented_text);
			}
			$this->write
				(
					\str_repeat(' ', $indent_hint).
					$indented_text
				);
		}
		else # word not longer then width
		{
			if ($nowrap_hint)
			{
				$text = \str_replace($nowrap_hint, ' ', $text);
			}
			$this->write($text);
		}
		
		return $this;
	}
	
	/**
	 * [!!] Must NEVER be used to convey functionality! 
	 * 
	 * If text is highlighted, then it should only to help make it easier to 
	 * convey the message not as a means of conveying the message. There is no 
	 * gurantee the writer will actually use the highlighting; or even knows of
	 * the provided highlighting hint. A log writer for example can't write to
	 * a plain/text file in color, so if messages are doubled to a log then
	 * messages that rely on color, bold, or underlines to convey the message 
	 * become unreadable garbage text.
	 * 
	 * @param string text
	 * @param string highlight key
	 * @return \ibidem\base\Writer_Console $this
	 */
	function highlight($text, $highlight = null)
	{
		if ( ! $this->highlight_support)
		{
			// no highlighting support
			$this->write($text);
		}
		else
		{
			if ($highlight === null || ! isset($this->highlight[$highlight]))
			{
				$highlight = $this->highlight[\ibidem\types\Enum_Color::Green];
			}
			else # got highlight
			{
				$highlight = $this->highlight[$highlight];
			}

			$output = \sprintf
				(
					'\e['.$highlight.'m%s\e[00m', 
					$text
				);
			
			if ($this->shell === null)
			{
				$this->write($output);
			}
			else # special output
			{
				// yo dwag heard you like shells, so I put a shell in your shell
				// so now you can execute php from your shell to your system's
				// shell, to bash, and back again to your shell; only on windows
				\system(\sprintf($this->shell, $output));
			}
		}
		
		return $this;
	}
	
	/**
	 * @param $args
	 * @return \ibidem\base\Writer_Console $this
	 */
	function writef($args)
	{
		$args = \func_get_args();
		\array_unshift($args, $this->stdout);
		\call_user_func_array('fprintf', $args);
		
		return $this;
	}
	
	/**
	 * @param string title
	 * @return \ibidem\base\Writer_Console $this
	 */
	function header($title)
	{
		if ($this->highlight_support)
		{
			$this->highlight(' '.$title, \ibidem\types\Enum_Color::Brown)->eol();
			$this->highlight(' '.\str_repeat('-', $this->width-1), \ibidem\types\Enum_Color::DarkGray);
			$this->eol()->eol();
		}
		else # no highlighting
		{
			\fprintf($this->stdout, ' '.$title.PHP_EOL);
			\fprintf($this->stdout, ' '.\str_repeat('-', $this->width-2).PHP_EOL.PHP_EOL);
		}
		return $this;
	}
	
	/**
	 * @param string title
	 * @return \ibidem\base\Writer_Console $this
	 */
	function subheader($title)
	{
		if ($this->highlight_support)
		{
			$this->highlight(' ===[ ', \ibidem\types\Enum_Color::DarkGray);
			$this->write($title);
			$this->highlight(' ]'.\str_repeat('=', $this->width-9-\strlen($title)), \ibidem\types\Enum_Color::DarkGray);
			$this->eol()->eol();
		}
		else # no highlighting
		{
			\fprintf
				(
					$this->stdout,
					' ===[ '.$title.' ]'.\str_repeat('=', $this->width-8-\strlen($title)).PHP_EOL.PHP_EOL
				);
		}
		
		return $this;
	}
	
	/**
	 * @param string text
	 * @return \ibidem\base\Writer_Console $this
	 */
	function status($status, $message, $highlight_hint = null)
	{
		if ($highlight_hint === null)
		{
			\fprintf
				(
					$this->stdout,
					" %10s %s",
					empty($status) ? '' : '['.$status.']', 
					$message
				);
		}
		else if ($this->highlight_support || $this->shell)
		{
			$status_length = \strlen($status);
			$this->write(\str_repeat(' ', 11 - 2 - $status_length).'[');
			$this->highlight($status, $highlight_hint);
			$this->writef('] %s', $message);
		}
		else # can't be done with highlighting
		{
			$this->status($status, $message);
		}
			
		return $this;
	}
	
	/**
	 * @param string text
	 * @return \ibidem\base\Writer_Console $this
	 */
	function error($text)
	{	
		if ($this->shell || $this->highlight_support)
		{
			$this->status('Error', $text, \ibidem\types\Enum_Color::Red);
		}
		else # plain text shell
		{
			\fprintf($this->stderr, ' %10s %s', '[Error]', $text);
		}
		
		return $this;
	}
	
} # class
