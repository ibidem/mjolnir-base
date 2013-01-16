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
	);