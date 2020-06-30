<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

/** 
 * Pre-System Hook to autoload called Base class on core/<name>_Controller.php
 * to separate Frontend base controller from admin
 *
 * Source: http://www.highermedia.com/articles/nuts_bolts/codeigniter_base_classes_revisited
 *
 * @modified by: Noel Ochoa <Noel.Ochoa@summitmedia.com.ph>
 */
$hook['pre_system'] = array(
	'class'    => 'MY_Autoloader',
	'function' => 'register',
	'filename' => 'MY_Autoloader.php',
	'filepath' => 'hooks',
	'params'   => array( APPPATH . 'core/' ) // add other desired paths to this array
);

$hook['post_controller_constructor'][] = array(
	'function' => 'force_ssl',
	'filename' => 'ssl.php',
	'filepath' => 'hooks'
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */