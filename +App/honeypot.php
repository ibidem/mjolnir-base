<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\base'

class Arr extends \mjolnir\base\Arr {}
class Assert extends \mjolnir\base\Assert { /** @return \mjolnir\base\Assert */ static function instance($expected = null) { return parent::instance($expected); } }
class Collection extends \mjolnir\base\Collection {}
class Controller_Web extends \mjolnir\base\Controller_Web { /** @return \mjolnir\base\Controller_Web */ static function instance() { return parent::instance(); } }
class Controller extends \mjolnir\base\Controller { /** @return \mjolnir\base\Controller */ static function instance() { return parent::instance(); } }
class Cookie extends \mjolnir\base\Cookie {}
class DateFormatter extends \mjolnir\base\DateFormatter { /** @return \mjolnir\base\DateFormatter */ static function instance() { return parent::instance(); } }
class Email extends \mjolnir\base\Email { /** @return \mjolnir\base\Email */ static function instance() { return parent::instance(); } }
class Exception_NotAllowed extends \mjolnir\base\Exception_NotAllowed {}
class Exception_NotApplicable extends \mjolnir\base\Exception_NotApplicable {}
class Exception_NotFound extends \mjolnir\base\Exception_NotFound {}
class Exception extends \mjolnir\base\Exception {}
class Flags extends \mjolnir\base\Flags {}
class GlobalEvent extends \mjolnir\base\GlobalEvent {}
class Instantiatable extends \mjolnir\base\Instantiatable { /** @return \mjolnir\base\Instantiatable */ static function instance() { return parent::instance(); } }
class Lang extends \mjolnir\base\Lang {}
class Layer_HTTP extends \mjolnir\base\Layer_HTTP { /** @return \mjolnir\base\Layer_HTTP */ static function instance() { return parent::instance(); } }
class Layer_JSend extends \mjolnir\base\Layer_JSend { /** @return \mjolnir\base\Layer_JSend */ static function instance() { return parent::instance(); } }
class Layer_MVC extends \mjolnir\base\Layer_MVC { /** @return \mjolnir\base\Layer_MVC */ static function instance() { return parent::instance(); } }
class Layer_PlainText extends \mjolnir\base\Layer_PlainText { /** @return \mjolnir\base\Layer_PlainText */ static function instance() { return parent::instance(); } }
class Layer_Sandbox extends \mjolnir\base\Layer_Sandbox { /** @return \mjolnir\base\Layer_Sandbox */ static function instance() { return parent::instance(); } }
class Layer_TaskRunner extends \mjolnir\base\Layer_TaskRunner { /** @return \mjolnir\base\Layer_TaskRunner */ static function instance() { return parent::instance(); } }
class Layer extends \mjolnir\base\Layer { /** @return \mjolnir\base\Layer */ static function instance() { return parent::instance(); } }
class Log extends \mjolnir\base\Log {}
class Mjolnir extends \mjolnir\base\Mjolnir {}
class Params extends \mjolnir\base\Params { /** @return \mjolnir\base\Params */ static function instance() { return parent::instance(); } }
class Query extends \mjolnir\base\Query {}
class Register extends \mjolnir\base\Register {}
class Relay extends \mjolnir\base\Relay {}
class Route_Path extends \mjolnir\base\Route_Path { /** @return \mjolnir\base\Route_Path */ static function instance($uri = null) { return parent::instance($uri); } }
class Route_Pattern extends \mjolnir\base\Route_Pattern { /** @return \mjolnir\base\Route_Pattern */ static function instance($the_uri = null) { return parent::instance($the_uri); } }
class Route_Regex extends \mjolnir\base\Route_Regex { /** @return \mjolnir\base\Route_Regex */ static function instance($uri = null) { return parent::instance($uri); } }
class Route extends \mjolnir\base\Route {}
class Sandbox extends \mjolnir\base\Sandbox { /** @return \mjolnir\base\Sandbox */ static function instance() { return parent::instance(); } }
class Schematic_Mjolnir_Base_Registry extends \mjolnir\base\Schematic_Mjolnir_Base_Registry { /** @return \mjolnir\base\Schematic_Mjolnir_Base_Registry */ static function instance() { return parent::instance(); } }
class Server extends \mjolnir\base\Server {}
class Session_Native extends \mjolnir\base\Session_Native { /** @return \mjolnir\base\Session_Native */ static function instance() { return parent::instance(); } }
class Session extends \mjolnir\base\Session {}
class Task_Behat extends \mjolnir\base\Task_Behat { /** @return \mjolnir\base\Task_Behat */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Cleanup extends \mjolnir\base\Task_Cleanup { /** @return \mjolnir\base\Task_Cleanup */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_Class extends \mjolnir\base\Task_Find_Class { /** @return \mjolnir\base\Task_Find_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_File extends \mjolnir\base\Task_Find_File { /** @return \mjolnir\base\Task_Find_File */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Honeypot extends \mjolnir\base\Task_Honeypot { /** @return \mjolnir\base\Task_Honeypot */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Class extends \mjolnir\base\Task_Make_Class { /** @return \mjolnir\base\Task_Make_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Config extends \mjolnir\base\Task_Make_Config { /** @return \mjolnir\base\Task_Make_Config */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Module extends \mjolnir\base\Task_Make_Module { /** @return \mjolnir\base\Task_Make_Module */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Trait extends \mjolnir\base\Task_Make_Trait { /** @return \mjolnir\base\Task_Make_Trait */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Relays extends \mjolnir\base\Task_Relays { /** @return \mjolnir\base\Task_Relays */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Status extends \mjolnir\base\Task_Status { /** @return \mjolnir\base\Task_Status */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Versions extends \mjolnir\base\Task_Versions { /** @return \mjolnir\base\Task_Versions */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task extends \mjolnir\base\Task { /** @return \mjolnir\base\Task */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Text extends \mjolnir\base\Text { /** @return \mjolnir\base\Text */ static function instance() { return parent::instance(); } }
trait Trait_Document { use \mjolnir\base\Trait_Document; }
trait Trait_Params { use \mjolnir\base\Trait_Params; }
class URL extends \mjolnir\base\URL {}
class View extends \mjolnir\base\View { /** @return \mjolnir\base\View */ static function instance($file = null) { return parent::instance($file); } }
class Writer_Console extends \mjolnir\base\Writer_Console { /** @return \mjolnir\base\Writer_Console */ static function instance() { return parent::instance(); } }
class Writer_Void extends \mjolnir\base\Writer_Void { /** @return \mjolnir\base\Writer_Void */ static function instance() { return parent::instance(); } }
