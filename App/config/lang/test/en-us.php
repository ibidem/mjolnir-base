<?php return array
	(
		'terms' => array
			(
				'test1' => 'success',
				'test2' => function (array $args) { return 'success';	},
			),
		'messages' => array
			(
				'test1' => 'success',
				'test2' => function (array $args) { return 'success';	},
			),
	);
