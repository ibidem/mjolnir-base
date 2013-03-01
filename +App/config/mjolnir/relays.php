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
				'action' => 'action_log',
			),
	
		'mjolnir:thumbnail.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('/thumbs/timthumb-2.8.10.php?src=<image>&w=<width>&h=<height>'),
				'enabled' => false, # should never be enabled; this is merely for creating urls
			)
	);