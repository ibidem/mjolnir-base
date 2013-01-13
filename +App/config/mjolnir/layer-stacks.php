<?php return array
	(
		# [!!] these names also act as extentions in routing! keep it simple

		// general purpose stack with domain access control, header processing
		// and a typical MVC structure
		'mvc' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relaynode);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_Access::instance(),
						\app\Layer_HTTP::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// logging stack; logging operations typically don't involve domain
		// level access control, if they do they should just use a mvc stack
		// instead of this one
		'log' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relaynode);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_HTTP::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// like the mvc stack only html's head section, scripts and so on is
		// processed at the domain level rather then the MVC level
		'html' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relaynode);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_Access::instance(),
						\app\Layer_HTTP::instance(),
						\app\Layer_HTML::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},

		// jsend is a json based communication protocol; the mvc layer can
		// output PHP variables (strings, arrays, etc) and they'll be wrapped
		// in the jsend protocol; exceptions will also trigger appropriate
		// behaviour
		'jsend' => function ($relay, $target)
			{
				$relaynode = \app\RelayNode::instance($relaynode);

				$channel = \app\Channel::instance()
					->set('relaynode', $relaynode);

				echo \app\Application::stack
					(
						\app\Layer_Access::instance(),
						\app\Layer_HTTP::instance(),
						\app\Layer_JSend::instance(),
						\app\Layer_MVC::instance()
					)
					->channel_is($channel)
					->recover_exceptions()
					->render();
			},
	);