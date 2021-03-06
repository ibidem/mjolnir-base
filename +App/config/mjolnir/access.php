<?php namespace app; return array
/////// Access Protocol Configuration //////////////////////////////////////////
(
	'whitelist' => array # allow
		(
			Auth::Guest => array
				(
					Allow::relays('mjolnir:error.log')
						->unrestricted(),

					Allow::relays('mjolnir:bootstrap.route')
						->unrestricted(),
				),

			'+common' => array
				(
					Allow::relays('mjolnir:error.log')
						->unrestricted(),
				),
		),

); # config
