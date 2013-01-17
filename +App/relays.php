<?php namespace app;

$log = \app\CFS::config('mjolnir/layer-stacks')['log'];

\app\Router::process('\mjolnir\error_log', $log);
