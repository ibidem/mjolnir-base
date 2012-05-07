<?php return array
	(
		'secure.default' => true, # all forms are fully secured; by default
		'method.default' => \ibidem\types\HTTP::POST,
		'template.field' => '<dt>:name</dt><dd>:field</dd>',
		'template.group' => '<fieldset><legend>:legend</legend>:fields</fieldset>',
	
		'textarea.rows.default' => 3,
		'textarea.cols.default' => 80,
	);
