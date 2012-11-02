<?php return array
	(
		'\mjolnir\sandbox' => array
			(
				'matcher' => \app\Route_Path::instance()->path('sandbox'),
				'enabled' => false,
			),
	
		'\mjolnir\error_log' => array
			(
				'matcher' => \app\Route_Pattern::instance()
					->standard('error-log', [])
					->canonical('error-log', []),
			
				'enabled' => true,
			
				'controller' => '\app\Controller_ClientErrors',
				'action' => 'action_log',
			),
	);