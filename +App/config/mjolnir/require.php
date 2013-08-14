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
					'extension=php_exif' => function ()
						{
							return \function_exists('\exif_read_data') ? 'satisfied' : 'error';
						},

					'cookie key' => function ()
						{
							$cookiekey = \app\CFS::config('mjolnir/cookies')['key'];
							if ($cookiekey !== null)
							{
								return 'satisfied';
							}

							return 'error';
						},

					 "ffmpeg" => function ()
						{
							if (\app\CFS::config('mjolnir/uploads')['video.upload.method'] !== 'direct')
							{
								return 'skipped';
							}

							try
							{
								$bin = \app\CFS::config('mjolnir/bin');
								if (\app\Shell::cmd_exists($bin['ffmpeg']))
								{
									return 'satisfied';
								}

								return 'failed';
							}
							catch (\Exception $e)
							{
								return 'failed';
							}
						},
					"mediainfo" => function ()
						{
							if (\app\CFS::config('mjolnir/uploads')['video.upload.method'] !== 'direct')
							{
								return 'skipped';
							}

							try
							{
								$bin = \app\CFS::config('mjolnir/bin');
								if (\app\Shell::cmd_exists($bin['mediainfo']))
								{
									return 'satisfied';
								}

								return 'failed';
							}
							catch (\Exception $e)
							{
								return 'failed';
							}
						},
					'non-dev email driver' => function ()
						{
							$email = \app\CFS::config('mjolnir/email');
							return ! \in_array($email['default.driver'], ['debug', 'file']) ? 'satisfied' : 'failed';
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

				return 'satisfied';
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

				return 'satisfied';
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

				return 'satisfied';
			};
	}

	return $require;
