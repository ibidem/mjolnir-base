<?php namespace app;

// This is a IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: minion honeypot -n "ibidem\\base"

class Collection extends \ibidem\base\Collection {}
class Controller_HTTP extends \ibidem\base\Controller_HTTP { /** @return \ibidem\base\Controller_HTTP */ static function instance() { return parent::instance(); } }
class Controller extends \ibidem\base\Controller { /** @return \ibidem\base\Controller */ static function instance() { return parent::instance(); } }
class DateFormatter extends \ibidem\base\DateFormatter { /** @return \ibidem\base\DateFormatter */ static function instance() { return parent::instance(); } }
class Event extends \ibidem\base\Event { /** @return \ibidem\base\Event */ static function instance() { return parent::instance(); } }
class Exception_NotAllowed extends \ibidem\base\Exception_NotAllowed {}
class Exception_NotApplicable extends \ibidem\base\Exception_NotApplicable {}
class Exception_NotFound extends \ibidem\base\Exception_NotFound {}
class Exception extends \ibidem\base\Exception {}
class Flags extends \ibidem\base\Flags {}
class Form extends \ibidem\base\Form { /** @return \ibidem\base\Form */ static function instance($id = null) { return parent::instance($id); } }
class FormField_Checkbox extends \ibidem\base\FormField_Checkbox { /** @return \ibidem\base\FormField_Checkbox */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_DateTime extends \ibidem\base\FormField_DateTime { /** @return \ibidem\base\FormField_DateTime */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Email extends \ibidem\base\FormField_Email { /** @return \ibidem\base\FormField_Email */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Hidden extends \ibidem\base\FormField_Hidden { /** @return \ibidem\base\FormField_Hidden */ static function instance($name = null, $form = null) { return parent::instance($name, $form); } }
class FormField_Password extends \ibidem\base\FormField_Password { /** @return \ibidem\base\FormField_Password */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Radio extends \ibidem\base\FormField_Radio { /** @return \ibidem\base\FormField_Radio */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Select extends \ibidem\base\FormField_Select { /** @return \ibidem\base\FormField_Select */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Submit extends \ibidem\base\FormField_Submit { /** @return \ibidem\base\FormField_Submit */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_Telephone extends \ibidem\base\FormField_Telephone { /** @return \ibidem\base\FormField_Telephone */ static function instance() { return parent::instance(); } }
class FormField_Text extends \ibidem\base\FormField_Text { /** @return \ibidem\base\FormField_Text */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField_TextArea extends \ibidem\base\FormField_TextArea { /** @return \ibidem\base\FormField_TextArea */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class FormField extends \ibidem\base\FormField { /** @return \ibidem\base\FormField */ static function instance($title = null, $name = null, $form = null) { return parent::instance($title, $name, $form); } }
class HTMLBlockElement extends \ibidem\base\HTMLBlockElement { /** @return \ibidem\base\HTMLBlockElement */ static function instance($name = 'div', $body = '') { return parent::instance($name, $body); } }
class HTMLElement extends \ibidem\base\HTMLElement { /** @return \ibidem\base\HTMLElement */ static function instance($name = 'hr') { return parent::instance($name); } }
class Instantiatable extends \ibidem\base\Instantiatable { /** @return \ibidem\base\Instantiatable */ static function instance() { return parent::instance(); } }
class Lang extends \ibidem\base\Lang {}
class Layer_HTML extends \ibidem\base\Layer_HTML { /** @return \ibidem\base\Layer_HTML */ static function instance() { return parent::instance(); } }
class Layer_HTTP extends \ibidem\base\Layer_HTTP { /** @return \ibidem\base\Layer_HTTP */ static function instance() { return parent::instance(); } }
class Layer_MVC extends \ibidem\base\Layer_MVC { /** @return \ibidem\base\Layer_MVC */ static function instance() { return parent::instance(); } }
class Layer_PlainText extends \ibidem\base\Layer_PlainText { /** @return \ibidem\base\Layer_PlainText */ static function instance() { return parent::instance(); } }
class Layer_Sandbox extends \ibidem\base\Layer_Sandbox { /** @return \ibidem\base\Layer_Sandbox */ static function instance() { return parent::instance(); } }
class Layer_TaskRunner extends \ibidem\base\Layer_TaskRunner { /** @return \ibidem\base\Layer_TaskRunner */ static function instance() { return parent::instance(); } }
class Layer extends \ibidem\base\Layer { /** @return \ibidem\base\Layer */ static function instance() { return parent::instance(); } }
class Log extends \ibidem\base\Log {}
class Make extends \ibidem\base\Make { /** @return \ibidem\base\Make */ static function instance($type = 'paragraph', array $args = null) { return parent::instance($type, $args); } }
class Migration_Template_MySQL extends \ibidem\base\Migration_Template_MySQL { /** @return \ibidem\base\Migration_Template_MySQL */ static function instance() { return parent::instance(); } }
class Migration extends \ibidem\base\Migration { /** @return \ibidem\base\Migration */ static function instance() { return parent::instance(); } }
class Model_Factory extends \ibidem\base\Model_Factory {}
class Model_Instantiatable extends \ibidem\base\Model_Instantiatable { /** @return \ibidem\base\Model_Instantiatable */ static function instance($id = null) { return parent::instance($id); } }
class Model_SQL_Factory extends \ibidem\base\Model_SQL_Factory {}
class Pager extends \ibidem\base\Pager { /** @return \ibidem\base\Pager */ static function instance($totalitems = 0, $url_base = '', $pagediff = 4, $pagelimit = 20) { return parent::instance($totalitems, $url_base, $pagediff, $pagelimit); } }
class Params extends \ibidem\base\Params { /** @return \ibidem\base\Params */ static function instance() { return parent::instance(); } }
class Relay extends \ibidem\base\Relay {}
class Route_Path extends \ibidem\base\Route_Path { /** @return \ibidem\base\Route_Path */ static function instance($uri = null) { return parent::instance($uri); } }
class Route_Pattern extends \ibidem\base\Route_Pattern { /** @return \ibidem\base\Route_Pattern */ static function instance($uri = null) { return parent::instance($uri); } }
class Route_Regex extends \ibidem\base\Route_Regex { /** @return \ibidem\base\Route_Regex */ static function instance($uri = null) { return parent::instance($uri); } }
class SQL extends \ibidem\base\SQL {}
class SQLDatabase extends \ibidem\base\SQLDatabase { /** @return \ibidem\base\SQLDatabase */ static function instance($database = 'default') { return parent::instance($database); } }
class SQLStatement extends \ibidem\base\SQLStatement { /** @return \ibidem\base\SQLStatement */ static function instance($statement = null) { return parent::instance($statement); } }
class Session_Native extends \ibidem\base\Session_Native { /** @return \ibidem\base\Session_Native */ static function instance() { return parent::instance(); } }
class Session extends \ibidem\base\Session {}
class Task_Check_Modules extends \ibidem\base\Task_Check_Modules { /** @return \ibidem\base\Task_Check_Modules */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_Class extends \ibidem\base\Task_Find_Class { /** @return \ibidem\base\Task_Find_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Find_File extends \ibidem\base\Task_Find_File { /** @return \ibidem\base\Task_Find_File */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Honeypot extends \ibidem\base\Task_Honeypot { /** @return \ibidem\base\Task_Honeypot */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Class extends \ibidem\base\Task_Make_Class { /** @return \ibidem\base\Task_Make_Class */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Config extends \ibidem\base\Task_Make_Config { /** @return \ibidem\base\Task_Make_Config */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Module extends \ibidem\base\Task_Make_Module { /** @return \ibidem\base\Task_Make_Module */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Migrate extends \ibidem\base\Task_Migrate { /** @return \ibidem\base\Task_Migrate */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Relays extends \ibidem\base\Task_Relays { /** @return \ibidem\base\Task_Relays */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Versions extends \ibidem\base\Task_Versions { /** @return \ibidem\base\Task_Versions */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task extends \ibidem\base\Task { /** @return \ibidem\base\Task */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Validator extends \ibidem\base\Validator { /** @return \ibidem\base\Validator */ static function instance(array $errors = null, array $fields = null) { return parent::instance($errors, $fields); } }
class ValidatorRules extends \ibidem\base\ValidatorRules {}
class View extends \ibidem\base\View { /** @return \ibidem\base\View */ static function instance($file = null) { return parent::instance($file); } }
class Writer_Console extends \ibidem\base\Writer_Console { /** @return \ibidem\base\Writer_Console */ static function instance() { return parent::instance(); } }
