<?php namespace app;

$log = \app\CFS::config('mjolnir/layer-stacks')['log'];

\app\Relay::process('\mjolnir\error_log', $log);
