<?php return array
	(
		'base_classes' => array
			(
				'#^Model_.*$#' => '\app\Model_SQL_Factory',
				'#^Controller_.*$#' => '\app\Controller_HTTP',
				'#^Migration_.*$#' => '\app\Migration_Template_MySQL',
			),
	);
