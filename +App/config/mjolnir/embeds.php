<?php return array
	(
		'youtube' => function ($identifier, $width = null, $height = null, $meta = null)
			{
				return \app\View::instance('mjolnir/base/embed.youtube')
					->pass('identifier', $identifier)
					->pass('width', $width)
					->pass('height', $height)
					->pass('meta', $meta)
					->render();
			},

		'vimeo' => function ($identifier, $width = null, $height = null, $meta = null)
			{
				return \app\View::instance('mjolnir/base/embed.vimeo')
					->pass('identifier', $identifier)
					->pass('width', $width)
					->pass('height', $height)
					->pass('meta', $meta)
					->render();
			},

	); # config
