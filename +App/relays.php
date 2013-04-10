<?php namespace app;

$log = \app\CFS::config('mjolnir/layer-stacks')['log'];
$raw = \app\CFS::config('mjolnir/layer-stacks')['raw'];

\app\Router::process('mjolnir:error/log.route', $log);
\app\Router::process('mjolnir:bootstrap.route', $raw);
