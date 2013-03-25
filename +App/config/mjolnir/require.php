<?php namespace app;

	if (\file_exists(Env::key('sys.path').'.www.path'))
	{
		$wwwpath = \rtrim(\trim(\file_get_contents(Env::key('sys.path').'.www.path')), '/\\');

		if (empty($wwwpath))
		{
			throw new \Exception('Invalid DOCROOT/.www.path');
		}
		else # got wwwpath
		{
			$wwwpath .= '/';
		}
	}
	else
	{
		$wwwpath = false;
	}

	$require = array
		(
			'mjolnir\base' => array
				(
					'cookie key' => function ()
						{
							$cookiekey = \app\CFS::config('mjolnir/cookies')['key'];
							if ($cookiekey !== null)
							{
								return 'available';
							}

							return 'error';
						},

					 "ffmpeg" => function ()
						{
							if (\app\Shell::cmd_exists('ffmpeg'))
							{
								return 'available';
							}

							return 'error';
						},
					"mediainfo" => function ()
						{
							if (\app\Shell::cmd_exists('mediainfo'))
							{
								return 'available';
							}

							return 'error';
						},
					'non-dev email driver' => function ()
						{
							$email = \app\CFS::config('mjolnir/email');
							return ! \in_array($email['default.driver'], ['debug', 'file']) ? 'available' : 'failed';
						}
				),
		);

	if ($wwwpath !== false)
	{
		$require['mjolnir\base'][\str_replace('\\', '/', "{$wwwpath}uploads")] = function () use ($wwwpath)
			{
				if ( ! \file_exists($wwwpath.'uploads/'))
				{
					return 'error';
				}

				if ( ! \is_writable($wwwpath.'uploads/'))
				{
					return 'error';
				}

				return 'available';
			};

		$require['mjolnir\base'][\str_replace('\\', '/', "{$wwwpath}thumbs")] = function () use ($wwwpath)
			{
				if ( ! \file_exists($wwwpath.'thumbs/'))
				{
					return 'error';
				}

				if ( ! \is_writable($wwwpath.'thumbs/'))
				{
					return 'error';
				}

				return 'available';
			};

		$require['mjolnir\base'][\str_replace('\\', '/', "{$wwwpath}media")] = function () use ($wwwpath)
			{
				if ( ! \file_exists($wwwpath.'media/'))
				{
					return 'error';
				}

				if ( ! \is_writable($wwwpath.'media/'))
				{
					return 'error';
				}

				return 'available';
			};
	}

	return $require;
