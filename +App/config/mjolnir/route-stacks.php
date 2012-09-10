<?php return array
	(
		'html' => function ($relay, $target)
			{
				\app\Layer::stack
					(
						\app\Layer_Access::instance()
							->relay_config($relay)
							->target($target),
						\app\Layer_HTTP::instance(),
						\app\Layer_HTML::instance(),
						\app\Layer_MVC::instance()
							->relay_config($relay)
					);
			},
					
		'json' => function ($relay, $target)
			{
				\app\Layer::stack
					(
						\app\Layer_Access::instance()
							->relay_config($relay)
							->target($target),
						\app\Layer_HTTP::instance(),
						\app\Layer_MVC::instance()
							->relay_config($relay)
					);
			},
	);