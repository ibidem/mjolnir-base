<?php return array
	(
		// driver used to fill in exchange rates
		'driver' => 'Google',

		// settings for Google driver
		'driver:Google' => array
			(
				// a req every 45min = ~1000 req/month
				'cache_multiplier' => 2700, # multiplied by types
			),

		// currency types; "rate" will be inserted automatically and is always
		// based on dollar, ie. dollar is always rate 1
		'types' => array
			(
				'EUR' => array
					(
						'title' => 'Euro',
						'symbol' => "€",
						'decimal' => ",",
						'thousand' => ".",
						'precision' => 2
					),

				'USD' => array
					(
						'title' => 'United States dollar',
						'symbol' => "$",
						'decimal' => ".",
						'thousand' => ",",
						'precision' => 2
					),
			),

	); # config
