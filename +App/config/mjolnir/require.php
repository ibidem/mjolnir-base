<?php namespace mjolnir\base;

use \mjolnir\types\Enum_Requirement as Requirement;

return array
	(
		'ibidem\base' => array
			(
				 'PHP 5.4 or higher' => function ()
					{
						if (PHP_VERSION_ID >= 50400)
						{
							return Requirement::available;
						}
						
						return Requirement::error;
					}
			),
	);
