<?php return array
	(
		/* Things to know about task configs...
		* 
		*  - if a flag is not mentioned for the command it won't be passed
		*  - configuration doubles as documentation
		*  - A null value for a flag's default means it's mandatory. 
		*  - A non-null value means it's optional
		*  - A false value means it's optional, but has no actual default value
		*  - "toggle" is a special type for boolean flags, no need to pass value
		*  - all "toggle" should have a default of false; using the flag => true
		*  - if you do not specify a type, "toggle" is assumed
		*  - if you do not specify a default, false is assumed
		*  - each entry in the array of the command's description is a paragraph
		*  - first entry in a command's description should be the oneline description
		*  - flag types can be methods in any class; preferably the Task_Class itself
		*  - you'll find general purpose tags in the Flags class
		* 
		* If you need a command along the lines of:
		*  
		*		minion some:command "something"
		*		(meaning no flags)
		* 
		* Just don't give it flags, handle it in the command's execution and explain it
		* in the command's documentation (ie. description). Preferably use flags though
		* and/or have that only as a shorthand and not as the only way.
		*/
		'make:config' => array
			(
				'description' => array
					(
						'Create master configuration file.', 
						'Use this command to generate new application level configuration files. The command will merge all configuration files thus computing a full overwrite.',
					),
				'flags' => array
					(
						'config' => array
							(
								'description' => 'Configuration path.',
								'short' => 'c',
								'type' => '\kohana4\base\Flags::text',
							),
						'forced' => array
							(
								'description' => 'Overwrites output file.',
							),
					),
			),
		'find:file' => array
			(
				'description' => array('List files based on environment.'),
				'flags' => array
					(
						'path' => array
							(
								'description' => 'Path to match files to.',
								'short' => 'p',
								'type' => '\kohana4\base\Flags::text',
							),
						'ext' => array
							(
								'description' => 'File extention',
								'short' => 'e',
								'type' => '\kohana4\base\Flags::text',
								'default' => EXT,
							),
					)
			),
		'find:class' => array
			(
				'description' => array('List class files, based on class.', 'The namespace is assumed to be \app, as in \app\Some_Class.'),
				'flags' => array
					(
						'class' => array
							(
								'description' => 'Class name for which to find files on the system.',
								'short' => 'c',
								'type' => '\kohana4\base\Flags::text',
							)
					)
			),
	);