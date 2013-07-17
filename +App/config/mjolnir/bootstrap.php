<?php return array
	(
		'mjolnir' => array
			(
				'routes' => array
					(
						'log' => \app\URL::href('mjolnir:error/log.route'),
					),
				'types' => array
					(
						'currency' => \app\Currency::information(),
					)
			),

	); # config
