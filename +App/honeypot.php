<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'ibidem\base'

class Assert extends \ibidem\base\Assert { /** @return \ibidem\base\Assert */ static function instance() { return parent::instance(); } }
class Collection extends \ibidem\base\Collection {}
class Controller_Web extends \ibidem\base\Controller_Web { /** @return \ibidem\base\Controller_Web */ static function instance() { return parent::instance(); } }
class Controller extends \ibidem\base\Controller { /** @return \ibidem\base\Controller */ static function instance() { return parent::instance(); } }
class DateFormatter extends \ibidem\base\DateFormatter { /** @return \ibidem\base\DateFormatter */ static function instance() { return parent::instance(); } }
class Email extends \ibidem\base\Email { /** @return \ibidem\base\Email */ static function instance() { return parent::instance(); } }
class Exception_NotAllowed extends \ibidem\base\Exception_NotAllowed {}
class Exception_NotApplicable extends \ibidem\base\Exception_NotApplicable {}
class Exception_NotFound extends \ibidem\base\Exception_NotFound {}
class Exception extends \ibidem\base\Exception {}
class Flags extends \ibidem\base\Flags {}
class GlobalEvent extends \ibidem\base\GlobalEvent {}
class Instantiatable extends \ibidem\base\Instantiatable { /** @return \ibidem\base\Instantiatable */ static function instance() { return parent::instance(); } }
class Lang extends \ibidem\base\Lang {}
class Layer_HTTP extends \ibidem\base\Layer_HTTP { /** @return \ibidem\base\Layer_HTTP */ static function instance() { return parent::instance(); } }
class Layer_MVC extends \ibidem\base\Layer_MVC { /** @return \ibidem\base\Layer_MVC */ static function instance() { return parent::instance(); } }
class Layer_PlainText extends \ibidem\base\Layer_PlainText { /** @return \ibidem\base\Layer_PlainText */ static function instance() { return parent::instance(); } }
class Layer_Sandbox extends \ibidem\base\Layer_Sandbox { /** @return \ibidem\base\Layer_Sandbox */ static function instance() { return parent::instance(); } }
class Layer_TaskRunner extends \ibidem\base\Layer_TaskRunner { /** @return \ibidem\base\Layer_TaskRunner */ static function instance() { return parent::instance(); } }
class Layer extends \ibidem\base\Layer { /** @return \ibidem\base\Layer */ static function instance() { return parent::instance(); } }
class Log extends \ibidem\base\Log {}
class Make extends \ibidem\base\Make { /** @return \ibidem\base\Make */ static function instance($type = 'paragraph', array $args = null) { return parent::instance($type, $args); } }
class Mjolnir extends \ibidem\base\Mjolnir {}
class Params extends \ibidem\base\Params { /** @return \ibidem\base\Params */ static function instance() { return parent::instance(); } }
class Register extends \ibidem\base\Register {}
class Relay extends \ibidem\base\Relay {}
class Route_Path extends \ibidem\base\Route_Path { /** @return \ibidem\base\Route_Path */ static function instance($uri = null) { return parent::instance($uri); } }
class Route_Pattern extends \ibidem\base\Route_Pattern { /** @return \ibidem\base\Route_Pattern */ static function instance($uri = null) { return parent::instance($uri); } }
class Route_Regex extends \ibidem\base\Route_Regex { /** @return \ibidem\base\Route_Regex */ static function instance($uri = null) { return parent::instance($uri); } }
class Route extends \ibidem\base\Route {}
class Sandbox extends \ibidem\base\Sandbox { /** @return \ibidem\base\Sandbox */ static function instance() { return parent::instance(); } }
class Schematic_Default_Ibidem_Base_Registry extends \ibidem\base\Schematic_Default_Ibidem_Base_Registry { /** @return \ibidem\base\Schematic_Default_Ibidem_Base_Registry */ static function instance() { return parent::instance(); } }
class Server extends \ibidem\base\Server {}
class Session_Native extends \ibidem\base\Session_Native { /** @return \ibidem\base\Session_Native */ static function instance() { return parent::instance(); } }
class Session extends \ibidem\base\Session {}
class Task_Check_Modules extends \ibidem\base\Task_Check_Modules { /** @return \ibidem\base\Task_Check_Modules */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_Class extends \ibidem\base\Task_Find_Class { /** @return \ibidem\base\Task_Find_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_File extends \ibidem\base\Task_Find_File { /** @return \ibidem\base\Task_Find_File */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Honeypot extends \ibidem\base\Task_Honeypot { /** @return \ibidem\base\Task_Honeypot */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Class extends \ibidem\base\Task_Make_Class { /** @return \ibidem\base\Task_Make_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Config extends \ibidem\base\Task_Make_Config { /** @return \ibidem\base\Task_Make_Config */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Module extends \ibidem\base\Task_Make_Module { /** @return \ibidem\base\Task_Make_Module */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Relays extends \ibidem\base\Task_Relays { /** @return \ibidem\base\Task_Relays */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Run_Behat extends \ibidem\base\Task_Run_Behat { /** @return \ibidem\base\Task_Run_Behat */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Versions extends \ibidem\base\Task_Versions { /** @return \ibidem\base\Task_Versions */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task extends \ibidem\base\Task { /** @return \ibidem\base\Task */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Text extends \ibidem\base\Text { /** @return \ibidem\base\Text */ static function instance() { return parent::instance(); } }
trait Trait_Document { use \ibidem\base\Trait_Document; }
trait Trait_Params { use \ibidem\base\Trait_Params; }
class URL extends \ibidem\base\URL {}
class View extends \ibidem\base\View { /** @return \ibidem\base\View */ static function instance($file = null) { return parent::instance($file); } }
class Writer_Console extends \ibidem\base\Writer_Console { /** @return \ibidem\base\Writer_Console */ static function instance() { return parent::instance(); } }
class Writer_Void extends \ibidem\base\Writer_Void { /** @return \ibidem\base\Writer_Void */ static function instance() { return parent::instance(); } }
