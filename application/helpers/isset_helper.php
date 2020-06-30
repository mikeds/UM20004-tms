<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Codeigniter isset Helper
 *
 * Easily use table_listing configurations.
 *
 * @author     Marknel Pineda <marknel.pineda23@gmail.com> | Claredil Poso <claredil.poso@gmail.com>
 * @copyright  Public Domain
 * @license    open_source
 *
 * @access  public
 * @param   string 
 * @param   array
 * @param   integer
 * @param   integer
 * @param   integer
 * @param   BOOL
 * @return  string or array
 */
if (! function_exists('_isset')){
	function _isset($variable) {
		$variable = isset($variable) ? (!empty($variable) ? $variable : '' ) : '';
		return $variable;
	}
}