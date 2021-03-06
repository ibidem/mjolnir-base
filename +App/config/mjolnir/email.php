<?php return array
	(
		// if you need to test set the driver to debug and the system will
		// output the message raw to screen so you can it's working as intended
		'default.driver' => 'native', # debug, file, native, sendmail, smtp

		'tries' => 5, # how many times to try to send the email

		// if loose is enabled email system will gracefully ignore cases where
		// null is passed in either to or from
		'loose' => false,

	# -- Driver Configuration ------------------------------------------------ #

		'sendmail:driver' => array
			(
				'options' => '/usr/sbin/sendmail -bs',
			),

		'smtp:driver' => array
			(
				'hostname' => null,
				'port' => 25,
				'encryption' => null,
				'username' => null,
				'password' => null,
				'timeout' => 10,
			),
	
	); # config
