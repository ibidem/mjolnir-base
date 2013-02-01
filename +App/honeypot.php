<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\base'

class Arr extends \mjolnir\base\Arr {}
class Cookie extends \mjolnir\base\Cookie {}
class Date extends \mjolnir\base\Date {}
class Email extends \mjolnir\base\Email { /** @return \mjolnir\base\Email */ static function instance($driver = null) { return parent::instance($driver); } }
class Filesystem extends \mjolnir\base\Filesystem {}
class Lang extends \mjolnir\base\Lang {}
class Session_Native extends \mjolnir\base\Session_Native { /** @return \mjolnir\base\Session_Native */ static function instance() { return parent::instance(); } }
class Session extends \mjolnir\base\Session {}
class Text extends \mjolnir\base\Text {}
class URL extends \mjolnir\base\URL {}
class View extends \mjolnir\base\View { /** @return \mjolnir\base\View */ static function instance($file = null, $ext = '.php') { return parent::instance($file, $ext); } }
