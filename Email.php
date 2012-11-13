<?php namespace mjolnir\base;

/**
 * @package    mjolnir
 * @category   Base
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Email extends \app\Instantiatable
{
	/**
	 * @var \Email
	 */
	protected $swift_mailer = null;

	/**
	 * @return \app\Email
	 */
	static function instance($driver = null)
	{
		$instance = parent::instance();

		$email_config = \app\CFS::config('mjolnir/email');

		// got driver? if not revert to configuration default
		$driver !== null or $driver = $email_config['default.driver'];

		switch ($driver)
		{
			case 'native':
				$transport = \Swift_MailTransport::newInstance();
				break;

			case 'sendmail':
				$transport = \Swift_MailTransport::newInstance
					(
						$email_config['sendmail:driver']['options']
					);
				break;

			case 'smtp':
				$config = $email_config['smtp:driver'];

				$transport = \Swift_SmtpTransport::newInstance
					(
						$config['hostname'],
						$config['port']
					);

				$transport->setEncryption($config['encryption']);

				if ( ! empty($config['username']) && ! empty($config['password']))
				{
					$transport->setUsername($config['username']);
					$transport->setPassword($config['password']);
				}

				$transport->setTimeout($config['timeout']);
				break;

			default:
				throw new \Exception('Unknown driver: '.$driver);
		}

		$instance->swift_mailer = \Swift_Mailer::newInstance($transport);

		return $instance;
	}

	/**
	 * "To" can be either string or array (email + name). You can also specify
	 * a list of "to"'s "cc"'s and "bcc"'s. "From" can be string or array
	 * as well.
	 *
	 * @return boolean success / failure
	 */
	public function send($to, $from, $subject, $message, $html = false)
	{
		$mimetype = $html ? 'text/html' : 'text/plain';
		$message = \Swift_Message::newInstance($subject, $message, $mimetype, 'utf-8');
		
		if ($from === null)
		{
			$base_config = \app\CFS::config('mjolnir/base');
			$from = 'do-not-reply@'.$base_config['domain'];
		}

		if (\is_string($to)) # single recipient?
		{
			$message->setTo($to);
		}
		elseif (\is_array($to)) # multiple recipients?
		{
			// name + email format? simply convert
			if (isset($to[0]) && isset($to[1]))
			{
				$to = ['to' => $to];
			}

			// process to, cc, bcc format
			foreach ($to as $method => $set)
			{
				// default to "to" in case of errors
				if ( ! \in_array($method, ['to', 'cc', 'bcc']))
				{
					$method = 'to';
				}

				$method = 'add'.ucfirst($method);

				// name + email format?
				if (\is_array($set))
				{
					// add a recipient with name
					$message->$method($set[0], $set[1]);
				}
				else # multiple reicepients
				{
					// add a recipient without name
					$message->$method($set);
				}
			}
		}

		if (\is_string($from))
		{
			// from without a name
			$message->setFrom($from);
		}
		elseif (\is_array($from))
		{
			// from with a name
			$message->setFrom($from[0], $from[1]);
		}

		return $this->process($message, \app\CFS::config('mjolnir/email')['tries']) !== 0;
	}

	// ------------------------------------------------------------------------
	// etc

	/**
	 * @return int number of emails sent
	 */
	private function process($message, $max_tries = 1, $attempt = 0)
	{
		static $last_error = 'No Error';

		if ($attempt >= $max_tries)
		{
			\mjolnir\log('Email', 'Failed to send email: '.$last_error);
			return 0;
		}

		try
		{
			++$attempt;
			return $this->swift_mailer->send($message);
		}
		catch (\Swift_IoException $e)
		{
			$last_error = $e->getMessage();
			return $this->process($message, $max_tries, $attempt);
		}
		catch (\Swift_TransportException $e)
		{
			$last_error = $e->getMessage();
			return $this->process($message, $max_tries, $attempt);
		}
		catch (\Exception $e)
		{
			\mjolnir\log('Email', 'Unexpected exception occured: '.$e->getMessage());
			\mjolnir\log_exception($e);
			return 0;
		}
	}

	// ------------------------------------------------------------------------
	// Validation helpers

	/**
	 * @return bool valid?
	 */
	static function valid($email)
	{
		// check length
		if (\strlen($email) > 254)
		{
			return false;
		}

		// check format
		if ( ! \preg_match('#^[^@]+@[^@]+\.[a-z]+#', $email))
		{
			return false;
		}

		return true;
	}

} # class
