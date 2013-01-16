<?php namespace mjolnir\base;

/**
 * This class is (at this point) identical to sandbox. In stack traces it can be
 * confusing to see sandbox on the layer stack, hence why this class is invoked
 * instead of Layer_Sandbox.
 * 
 * @package    mjolnir
 * @category   Layer
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_ErrorHandler extends \app\Layer_Sandbox
{
	// no additional implementation required

} # class
