<?php return array
	(
		'errors' => array
			(
				'not_empty' => \app\Lang::term('Field is required.'),
				'valid' => \app\Lang::term('Invalid value.'),
				'exists' => \app\Lang::term('Invalid value.'),
			),

		'rules' => array
			(
				'not_empty' => function ($fields, $field)
					{
						return $fields[$field] === 0
							|| $fields[$field] === '0'
							|| ! empty($fields[$field]);
					},

				'valid_number' => function ($fields, $field)
					{
						return \is_numeric($fields[$field]);
					},
			),
	);
