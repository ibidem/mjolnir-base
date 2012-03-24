<?php return array
	(
		// [!!] enabling highlighting on windows (which doesn't support it) will
		// work, but the workarounds to get it to work may cause slowdown
		// [!!] with highlighting on stderr output is not guranteed when using
		// the error method; relevant when buffering
		'highlighting' => false, 
		// maximum width of the console window
		'width' => 80,
		// highlight mappings; can be colors or anything
		'highlight' => array
			(
				\kohana4\types\Enum_Color::Black       => '0;30',
				\kohana4\types\Enum_Color::Red         => '0;31',
				\kohana4\types\Enum_Color::Green       => '0;32',
				\kohana4\types\Enum_Color::Blue        => '0;34',
				\kohana4\types\Enum_Color::Purple      => '0;35',
				\kohana4\types\Enum_Color::Cyan        => '0;36',
				\kohana4\types\Enum_Color::White       => '0;37',
				\kohana4\types\Enum_Color::Brown       => '0;33',
				\kohana4\types\Enum_Color::LightGray   => '0;37',
				\kohana4\types\Enum_Color::DarkGray    => '1;30',
				\kohana4\types\Enum_Color::LightBlue   => '1;34',
				\kohana4\types\Enum_Color::LightGreen  => '1;32',
				\kohana4\types\Enum_Color::LightCyan   => '1;36',
				\kohana4\types\Enum_Color::LightRed    => '1;31',
				\kohana4\types\Enum_Color::LightPurple => '1;35',
				\kohana4\types\Enum_Color::Yellow      => '1;33',
			)
	);
