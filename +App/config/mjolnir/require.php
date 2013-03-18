<?php 

if (\file_exists(DOCROOT.'pubdirs'))
{
	$pubdirs = \explode("\n", \trim(\file_get_contents(DOCROOT.'pubdirs')));
	
	foreach ($pubdirs as &$pubdir)
	{
		$pubdir = \ltrim(\trim($pubdir), '/\\');
		
		if (empty($pubdir))
		{
			throw new \Exception('Invalid entry in DOCROOT/pubdirs.');
		}
		else
		{
			$pubdir .= '/';
		}
	}
}
else # no pubdir file defined
{
	throw new \Exception('Please define a DOCROOT/pubdirs file with the location to your public directories seperated by line breaks.');
}

$require = array
	(
		'mjolnir\base' => array
			(
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
			),
	);
	
// provide tests for all registered public applications
foreach ($pubdirs as $pubdir)
{				
	$require['mjolnir\base'][\str_replace('\\', '/', "{$pubdir}uploads")] = function () use ($pubdir)
		{
			if ( ! \file_exists($pubdir.'uploads/'))
			{
				return 'error';
			}

			if ( ! \is_writable($pubdir.'uploads/'))
			{
				return 'error';
			}

			return 'available';
		};
		
	$require['mjolnir\base'][\str_replace('\\', '/', "{$pubdir}thumbs")] = function () use ($pubdir)
		{
			if ( ! \file_exists($pubdir.'thumbs/'))
			{
				return 'error';
			}

			if ( ! \is_writable($pubdir.'thumbs/'))
			{
				return 'error';
			}

			return 'available';
		};
		
	$require['mjolnir\base'][\str_replace('\\', '/', "{$pubdir}media")] = function () use ($pubdir)
		{
			if ( ! \file_exists($pubdir.'media/'))
			{
				return 'error';
			}

			if ( ! \is_writable($pubdir.'media/'))
			{
				return 'error';
			}

			return 'available';
		};
}

return $require;
