<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\base'


class Arr extends \mjolnir\base\Arr
{
}

class Cookie extends \mjolnir\base\Cookie
{
}

class Date extends \mjolnir\base\Date
{
}

class Debug extends \mjolnir\base\Debug
{
}

class Email extends \mjolnir\base\Email
{
	/** @return \app\Email */
	static function instance($driver = null) { return parent::instance($driver); }
}

class Filesystem extends \mjolnir\base\Filesystem
{
}

/**
 * @method \app\Lang addlibrary($librarykey)
 */
class Lang extends \mjolnir\base\Lang
{
	/** @return \app\Lang */
	static function instance() { return parent::instance(); }
}

class Media extends \mjolnir\base\Media
{
}

/**
 * @method \app\Session_Native set($key, $value)
 * @method \app\Session_Native populate_params(array $params)
 * @method \app\Session_Native add($name, $value)
 * @method \app\Session_Native metadata_is(array $metadata = null)
 */
class Session_Native extends \mjolnir\base\Session_Native
{
	/** @return \app\Session_Native */
	static function instance() { return parent::instance(); }
}

class Session extends \mjolnir\base\Session
{
}

class Shell extends \mjolnir\base\Shell
{
	/** @return \app\Shell */
	static function instance() { return parent::instance(); }
}

class Text extends \mjolnir\base\Text
{
}

class URL extends \mjolnir\base\URL
{
	/** @return \app\URLCompatible */
	static function route($key) { return parent::route($key); }
}

class VideoConverter_FFmpeg extends \mjolnir\base\VideoConverter_FFmpeg
{
	/** @return \app\VideoConverter_FFmpeg */
	static function instance() { return parent::instance(); }
}

class VideoConverter extends \mjolnir\base\VideoConverter
{
}

/**
 * @method \app\View file_is($file, $ext = '.php')
 * @method \app\View bind($name,  & $non_object)
 * @method \app\View pass($name, $value)
 * @method \app\View inherit($view)
 * @method \app\View addmetarenderer($key, $metarenderer)
 * @method \app\View injectmetarenderers(array $metarenderers = null)
 * @method \app\View file_path($filepath)
 */
class View extends \mjolnir\base\View
{
	/** @return \app\View */
	static function instance($file = null, $ext = '.php') { return parent::instance($file, $ext); }
}

/**
 * @method \app\ViewComposite views_are(array $views)
 * @method \app\ViewComposite bind($name,  & $non_object)
 * @method \app\ViewComposite pass($name, $value)
 * @method \app\ViewComposite inherit($view)
 * @method \app\ViewComposite addmetarenderer($key, $metarenderer)
 * @method \app\ViewComposite injectmetarenderers(array $metarenderers = null)
 */
class ViewComposite extends \mjolnir\base\ViewComposite
{
	/** @return \app\ViewComposite */
	static function instance() { return parent::instance(); }
}
