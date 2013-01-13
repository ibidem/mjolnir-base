<?php return array
	(
		'\mjolnir\sandbox' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('sandbox'),
				'enabled' => false,
			),

		'\mjolnir\error_log' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('error-log'),
				'enabled' => true,
			// MVC
				'controller' => '\app\Controller_ClientErrors',
				'action' => 'action_log',
			),
	);