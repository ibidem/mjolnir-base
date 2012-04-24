<?php return array
	(
		'base_classes' => array
			(
				'#^Model_.*$#' => '\\app\\Model_Factory',
				'#^Controller_.*$#' => '\\app\\Controller',
			),
	);
