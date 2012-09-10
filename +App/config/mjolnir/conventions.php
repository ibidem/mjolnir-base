<?php return array
	(
		'base_classes' => array
			(
				'#^Controller_.*$#' => '\app\Controller_Web',
				'#^Task_.*$#' => '\app\Task',
				'#^Layer_.*$#' => '\app\Layer',
			),
	
		'autofills' => array
			(
				'#^Controller_.*$#' => \app\View::instance('mjolnir/base/autofills/Controller')->render(),
				'#^Task_.*$#' => \app\View::instance('mjolnir/base/autofills/Task')->render(),
				'#^Layer_.*$#' => \app\View::instance('mjolnir/base/autofills/Layer')->render(),
			),
	);
