<?php namespace app;

$errorlog_stack = function ($relay, $target)
	{
		\app\Layer::stack
			(
				\app\Layer_HTTP::instance(),
				\app\Layer_MVC::instance()
					->relay_config($relay)
			);
	};

\app\Relay::process('\mjolnir\error_log', $errorlog_stack);