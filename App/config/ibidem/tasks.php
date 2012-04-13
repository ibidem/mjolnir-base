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
		'check:modules' => array
			(
				'description' => array
					(
						'Perform all environment checks.',
						'Modules should provide tests for all their dependencies, that ensure they will function properly in their environement.'
					),
				'flags' => array
					(
						'no-stop' => array
							(
								'description' => 'Do not stop on errors.'
							),
						'strict' => array
							(
								'description' => 'Treat failed as errors.'
							),
					),
			),
		'check:relays' => array
			(
				'desctiption' => array
					(
						'Lists (enabled) relays.'
					),
			),
		'make:class' => array
			(
				'description' => array
					(
						'Create class.'
					),
				'flags' => array
					(
						'class' => array
							(
								'description' => 'Class; with namespace.',
								'type' => '\ibidem\base\Flags::text',
								'short' => 'c',
							),
						'category' => array
							(
								'description' => 'Class category.',
								'type' => '\ibidem\base\Flags::text',
								'short' => 'g',
								'default' => null,
							),
						'no-tests' => array
							(
								'description' => 'Do not create tests?'
							),
						'library' => array
							(
								'description' => 'Library class? (ie. not instantiatable)',
								'short' => 'l',
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),
		'make:module' => array
			(
				'description' => array
					(
						'Create a basic module.'
					),
				'flags' => array
					(
						'name' => array
							(
								'description' => 'Name of module',
								'type' => '\ibidem\base\Flags::text',
							),
						'namespace' => array
							(
								'description' => 'Namespace of module.',
								'type' => '\ibidem\base\Flags::text',
								'short' => 'n',
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),
		'versions' => array
			(
				'description' => array
					(
						'Print version info, as defined by modules.'
					),
			),
		'honeypot' => array
			(
				'description' => array
					(
						'Generate honeypot files.',
						'Honeypot files allow for IDEs to understand the app namespace and hence the connection between file hirarchies and calls.',
						'Namespace modules do not need honeypot files; attempting to generate one will result in errors. You should not have \app\Some_Namespace calls, as namespaces SHOULD always be final.'
					),
				'flags' => array
					(
						'namespace' => array
							(
								'description' => 'Namespace of target module.',
								'short' => 'n',
								'type' => '\ibidem\base\Flags::text',
							),
					),
			),
		'migrate' => array
			(
				'description' => array
					(
						'Database migrations.'
					),
				'flags' => array
					(
						'install' => array
							(
								'description' => 'Initialize and migrate to latest versions.',
								'short' => 'i',
							),
						'uninstall' => array
							(
								'description' => 'Uninstall everything and cleanup.',
								'short' => 'u',
							),
						'list' => array
							(
								'description' => 'List all versions. (default)',
							),
					),
			),
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
								'type' => '\ibidem\base\Flags::text',
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
								'type' => '\ibidem\base\Flags::text',
							),
						'ext' => array
							(
								'description' => 'File extention',
								'short' => 'e',
								'type' => '\ibidem\base\Flags::text',
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
								'type' => '\ibidem\base\Flags::text',
							)
					)
			),
	);
