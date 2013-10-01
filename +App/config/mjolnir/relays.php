<?php return array
	(
		'mjolnir:sandbox.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('sandbox'),
				'enabled' => false,
			),

		'mjolnir:thumbnail.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('thumbs/timthumb.php?src=<image>&w=<width>&h=<height>'),
				'enabled' => false, # should never be enabled; this is merely for creating urls
			),

		'mjolnir:video.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('uploads/<video>'),
				'enabled' => false, # should never be enabled; this is merely for creating urls
			),

	); # config
