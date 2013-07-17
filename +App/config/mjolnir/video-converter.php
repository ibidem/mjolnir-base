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
								'flv' => '-ar 44100 -qscale:v 1',
								'ogv' => '-acodec libvorbis -vcodec libtheora -b 1000k',
							),
						'ogv' => array
							(
								'mp4' => '-b:v 1000k',
								'webm' => '-b:v 1000k',
								'flv' => '-b:v 1000k',
							),
						'mov' => array
							(
								'flv' => '-ar 44100 -qscale:v 1',
							)
					)
			)
	);