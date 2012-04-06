<?php namespace ibidem\base;

use \ibidem\types\Enum_Requirement as Requirement;

return array
	(
		'ibidem\base' => array
			(
				 'PHP 5.3 or higher' => function ()
					{
						if (PHP_VERSION_ID >= 50300)
						{
							return Requirement::available;
						}
						
						return Requirement::error;
					}
			),
	);
