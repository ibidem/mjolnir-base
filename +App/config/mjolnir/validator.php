<?php return array
	(
		'errors' => array
			(
				'not-empty' => \app\Lang::term('Field is required.'),
				'valid' => \app\Lang::term('Invalid value.'),
				'valid-number' => \app\Lang::term('Field must be valid number.'),
				'exists' => \app\Lang::term('Invalid value.'),
				'unique' => \app\Lang::term('Value must be unique.'),
			),

		'rules' => array
			(
				'not-empty' => function ($fields, $field)
					{
						return $fields[$field] === 0
							|| $fields[$field] === '0'
							|| ! empty($fields[$field]);
					},

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

	); # config
