<?php return array
	(
		'driver' => 'FFmpeg',
	
		# -------------------------------------------------------------------- #
	
		'FFmpeg.driver' => array
			(
				// settings will be passed to ffmpeg when converting
				// read bellow as: source => target => settings
				'settings' => array
					(
						'mp4' => array
							(
								'flv' => '-ar 44100 -qscale:v 1'
							),
						'ogv' => array
							(
								'mp4' => '-b:v 1000k'
							)
					)
			)
	);