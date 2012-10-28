<?php return array
	(
		'default.driver' => 'native', # native, sendmail, smtp

		'tries' => 5, # how many times to try to send the email

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
	);