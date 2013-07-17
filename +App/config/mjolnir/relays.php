<?php return array
	(
		'mjolnir:sandbox.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('sandbox'),
				'enabled' => false,
			),

		'mjolnir:error/log.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('error-log'),
				'enabled' => true,
			// MVC
				'controller' => '\app\Controller_ClientErrors',
				'action' => 'log',
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

		'mjolnir:bootstrap.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('system/bootstrap.json'),
				'enabled' => true,
			// MVC
				'controller' => '\app\Controller_Bootstrap',
				'prefix' => 'json_',
				'action' => 'index',
			),
	);