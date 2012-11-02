<?php namespace app; return array
/////// Access Protocol Configuration //////////////////////////////////////////
(
	'whitelist' => array # allow
		(
			A12n::guest() => array
				(
					Allow::relays('\mjolnir\error_log')
						->all_parameters(),
				),

			'+common' => array
				(
					Allow::relays('\mjolnir\error_log')
						->all_parameters(),
				),
		),
);
