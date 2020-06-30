<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Utility Helper 
 * Extension of URL Helper
 * 
 * NOTE: requires url_helper, text_helper
 *
 * @author: Marknel Pineda<marknel.pineda23@gmail.com>
 * @date created: 05/14/2013 5:33 PM
 */
 
// ------------------------------------------------------------------------

if (! function_exists('null_to_empty')){
	function null_to_empty($variable) {
		$variable = is_null($variable) ? "" : $variable;
		return $variable;
	}
}

/**
 * 
 * Returns URL of CMS
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('unserialize_data'))
{
	function unserialize_data($str) {
		$is_serialized = ($str == serialize(false) || @unserialize($str) !== false);

		if (!$is_serialized) {
			return false;	
		} else {
			return unserialize($str);
		}
	}
}

if ( ! function_exists('assets_url'))
{
	function assets_url() {
		return base_url() . "assets/frameworks/bmtg/";
	}
}

if ( ! function_exists('date_to_age'))
{
	function date_to_age($dob) {
		return date_diff(date_create($dob), date_create('now'))->y;
	}
}

if ( ! function_exists('cm_to_m'))
{
	function cm_to_m($centimeter) {
		return $centimeter / 100;
	}
}

if ( ! function_exists('calculate_bmi'))
{
	function calculate_bmi($kg, $meter) {
		return number_format(( $kg / $meter) / $meter, 1);
	}
}

function number_to_roman_representation($number) {
	$map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	$returnValue = '';
	while ($number > 0) {
		foreach ($map as $roman => $int) {
			if($number >= $int) {
				$number -= $int;
				$returnValue .= $roman;
				break;
			}
		}
	}
	return $returnValue;
}

function roman_representation_to_number($roman) {
	$romans = array(
		'M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1,
	);
	
	$result = 0;
	
	foreach ($romans as $key => $value) {
		while (strpos($roman, $key) === 0) {
			$result += $value;
			$roman = substr($roman, strlen($key));
		}
	}
	return $result;
}

function is_subdomain() {
	$http_host 	= HTTP_HOST; // from constant varialble constants.php
	$host_arr 	= explode(".", $http_host);

	if (count($host_arr) > 2) {
		return true;
	}

	return false;
}

function contains_array($array){
    foreach($array as $value){
        if(is_array($value)) {
          return true;
        }
    }
    return false;
}

if (!function_exists("seconds_to_time")) {
	function seconds_to_time($seconds) {
		$years = abs(floor($seconds / 31536000));
		$days = abs(floor(($seconds-($years * 31536000))/86400));
		$hours = abs(floor(($seconds-($years * 31536000)-($days * 86400))/3600));
		$mins = abs(floor(($seconds-($years * 31536000)-($days * 86400)-($hours * 3600))/60));#floor($difference / 60);
		$sec = abs(floor(($seconds-($years * 31536000)-($days * 86400)-($hours * 3600)-($mins * 60)))); #floor($difference / 60);

		return array(
			'years'		=> $years,
			'days'		=> $days,
			'hours'		=> $hours,
			'minutes'	=> $mins,
			'seconds'	=> $sec,
		);
	}
}

if ( ! function_exists("date_valid")) {
	function date_valid($date){
		$day = (int) substr($date, 8, 2);
		$month = (int) substr($date, 5, 2);
		$year = (int) substr($date, 0, 4);
		// return true;
		return checkdate($month, $day, $year);
	}
}

if ( ! function_exists("isJson")) {
	function isJson($string){
		$array = json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE ? $array : array());
	}
}

if ( ! function_exists("generate_code"))
{
	function generate_code($length, $type = 1) {
		// 1 = number/letter
		// 2 = number
		// 3 = letter
		// $length = how many digits or characters to return.
		// You can use any set of characters you want.
		$possible = '0123456789abcdefghijkmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		//$possible = '0123456789';
		$code = '';
		$i = 0;
		while ($i < $length) { 
		$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if($i< $length-1){
			$code .= "";
			}
		$i++;
		}
		return $code;
	}
}

if ( ! function_exists("get_timeago"))
{
	function get_timeago( $ptime )
	{
	    $estimate_time = time() - $ptime;

	    if( $estimate_time < 1 )
	    {
	        // return 'less than 1 second ago';
	        return 'just now';
	    }

	    $condition = array( 
	                12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	    );

	    foreach( $condition as $secs => $str )
	    {
	        $d = $estimate_time / $secs;

	        if( $d >= 1 )
	        {
	            $r = round( $d );
	            return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
	        }
	    }
	}
}
if ( !function_exists("total")){
	function total( $items = array(), $percentage = false ){
		$data 	= array();
		$total 	= 0;
		$mark = "";

		if($percentage){
			$mark = "%";
		}
		// $count 	= 0;
		foreach ($items as $item) {
			$total += $item;
			$data[count($data)] = $item.$mark;
		}
		$data[count($data)] = $total;
		return $data;
	}
}

function grand_total( $items = array(), $month = 0 ){
	// colections of total
	$data 		= array();
	foreach ($items as $item) {
		$count = 0;
		$sdata	 	= array();
		foreach ($item as $sitem) {
			if( $month == 0 ){
				$sdata[$count] = abs( ( count($data) != 0 ? $data[$count] : 0 ) + $sitem );
			}else{
				if( $count == $month - 1 ){
					$sdata[] = abs( ( count($data) != 0 ? $data[count($data) - 1] : 0 ) + $sitem );
				}
			}

			$count++;
		}
		$data = $sdata;
	}
	
	return $data;
}

function grand_rating( $counts = array(), $sums = array(), $month = 0 ){
	// colections of total
	$data 		= array();
	$count 		= 0;
	foreach ($counts as $item) {
		if( $month == 0 ){
			$data[count($data)] = get_rating(  $item, $sums[$count] );
		}else{
			if( $count == $month - 1){
				$data[count($data)] = get_rating(  $item, $sums[$count] );
			}
		}
		$count++;
	}

	return $data;
}


function get_average( $count = 0, $sum = 0 ){
		
	$avg = 0;

	if($count == 0 || $sum == 0){
		// do nothing
	}else{
		$avg = round($sum / $count);
	}

	return $avg;
}

function get_rating( $count = 0, $score = 0){
	
	$rate = 0;

	if($count == 0 || $score == 0){
		// do nothing
	}else{
		$rate = round( ($score * 100) / $count );
	}

	return $rate."%";
}

if ( !function_exists("redirect_back")){
	function redirect_back( $url ){
		$CI =& get_instance();
		$page = $CI->session->userdata('database_redirect');
		if(!empty($page)){
			redirect($url.DIR_SEPARATOR.$page);
		}else{
			redirect($url);
		}
	}
}

if ( ! function_exists("split") ){
	function split( $separator, $string )
	{
		$arr = explode($separator, $string);
		return $arr;
	}
}


if ( ! function_exists("show_user_login"))
{
	function show_user_login()
	{
		redirect('user/login');
	}
}

if ( ! function_exists("show_admin_login"))
{
	function show_admin_login()
	{
		redirect('admin/login');
	}
}

if ( ! function_exists("to_trimname"))
{
	function to_trimname( $str = '' )
	{
		return strtolower( str_replace(' ', '-', preg_replace('/\s+/', ' ', trim($str) ) ) );
	}
}


if ( ! function_exists("to_trimname_rev"))
{
	function to_trimname_rev( $str = '' )
	{
		return strtolower( str_replace('-', '_', preg_replace('/\s+/', ' ', trim($str) ) ) );
	}
}

if ( ! function_exists("_prefix"))
{
	function _prefix()
	{
		return "tbl_";
	}
}

if ( ! defined( 'DIR_SEPARATOR' )) 
{
	define( 'DIR_SEPARATOR', '/' );
}


/**
 * Admin URL
 * 
 * Returns URL of CMS
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('member_url'))
{
	function member_url()
	{
		return base_url() . 'member' . DIR_SEPARATOR;
	}
}
/**
 * User URL
 * 
 * Returns URL of CMS
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('user_url'))
{
	function user_url()
	{
		return base_url() . 'user' . DIR_SEPARATOR;
	}
}

/**
 * Cashier URL
 * 
 * Returns URL of CMS
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('cashier_url'))
{
	function cashier_url()
	{
		return base_url() . 'cashier' . DIR_SEPARATOR;
	}
}

/**
 * Admin URL
 * 
 * Returns URL of CMS
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('admin_url'))
{
	function admin_url()
	{
		return base_url() . 'admin' . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Admin Controller URL
 * 
 * Builds CMS URL until Controller level
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('admin_controller_url'))
{
	function admin_controller_url()
	{
		$CI =& get_instance();
		return admin_url() . $CI->router->fetch_class() . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Controller URL
 * 
 * Builds URL until Controller level
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('controller_url'))
{
	function controller_url()
	{
		$CI =& get_instance();
		return base_url() . $CI->router->fetch_class() . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Get Controller
 * 
 * Returns current controller
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('get_controller'))
{
	function get_controller()
	{
		$CI =& get_instance();
		return $CI->router->fetch_class();
	}
}

// ------------------------------------------------------------------------

/**
 * Admin Action URL
 * 
 * Builds CMS URL until Controller action/ method
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('admin_action_url'))
{
	function admin_action_url()
	{
		$CI =& get_instance();
		return admin_url() . $CI->router->fetch_class() . DIR_SEPARATOR . $CI->router->fetch_method() . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Action URL
 * 
 * Builds URL until Controller action/ method
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('action_url'))
{
	function action_url()
	{
		$CI =& get_instance();
		return base_url() . $CI->router->fetch_class() . DIR_SEPARATOR . $CI->router->fetch_method() . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Get Action
 * 
 * Returns current action/ method
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('get_action'))
{
	function get_action()
	{
		$CI =& get_instance();
		return $CI->router->fetch_method();
	}
}

// ------------------------------------------------------------------------

/**
 * Public URL
 * 
 * Returns the url for assets (images, css, js, etc)
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('public_url'))
{
	function public_url()
	{
		return base_url() . 'public' . DIR_SEPARATOR;
	}
}


/**
 * Month # to text
 * 
 * Returns the url for assets (images, css, js, etc)
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('month_to_text'))
{
	function month_to_text($n = 1)
	{
		$text = "jan";

		if($n == 1){
			$text = "jan";
		}else if($n == 2){
			$text = "feb";
		}else if($n == 3){
			$text = "mar";
		}else if($n == 4){
			$text = "apr";
		}else if($n == 5){
			$text = "may";
		}else if($n == 6){
			$text = "jun";
		}else if($n == 7){
			$text = "jul";
		}else if($n == 8){
			$text = "aug";
		}else if($n == 9){
			$text = "sep";
		}else if($n == 10){
			$text = "oct";
		}else if($n == 11){
			$text = "nov";
		}else if($n == 12){
			$text = "dec";
		}

		return $text;
	}
}


// ------------------------------------------------------------------------

/**
 * Uploads path URL
 * 
 * Convert empty value to NULL
 *
 * @access	public
 * @return	string/ NULL
 */
if ( ! function_exists('upload_path'))
{
	function upload_path()
	{
		return base_url() . 'uploads' . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------


/**
 * Create URL Title
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the separator: dash, or underscore
 * @return	string
 */
if ( ! function_exists('create_url_title'))
{
	function create_url_title($str, $separator = 'dash', $lowercase = FALSE)
	{
		$replace = $separator == 'dash' ? '-' : '_';
		
		$trans = array(
			'&.+?;'			=> '',
			'[^a-z0-9 _-]'	=> '',
			'\s+'			=> $replace,
			$replace.'+'	=> $replace
		);
		
		// text helper is required
		$str = convert_accented_characters( strip_tags($str) );
		
		// change all underscores to dash
		if( $replace != '_' ) {
			$str = str_replace( '_', '-', $str );
		}
		
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = strtolower($str);
		}

		return trim($str, $replace);
	}
}

// ------------------------------------------------------------------------

/**
 * Vars Path
 * 
 * Returns path of vars folder 
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('vars_path'))
{
	function vars_path()
	{
		return APPPATH . 'vars' . DIR_SEPARATOR;
	}
}

// ------------------------------------------------------------------------

/**
 * Sanitize Output
 * 
 * Extra filter for XSS before outputting to views
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('sanitize_output'))
{
	function sanitize_output( $mixed )
	{
		if( is_array( $mixed ) ) {
			array_walk_recursive( $mixed, function ( &$value ) {
				$value = htmlentities( xss_clean( $value ) );
			});
		}
		else {
			$mixed = htmlentities( xss_clean( $mixed ) );
		}

		return $mixed;
	}
}

// ------------------------------------------------------------------------

/**
 * Array search key recursive
 * 
 * Searches an array if key exists then return value
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('array_search_key'))
{
	function array_search_key( $array, $key )
	{
		foreach( $array as $k => $each) {
			if( $k == $key ) {
				return $each;
			}
			
			if( is_array( $each ) ) {
				if( $return = array_search_key( $each, $key ) ) {
					return $return;
				}
			}
		}
	}
}

if ( ! function_exists('in_arrayr'))
{
	function in_arrayr($needle, $haystack) { 
        foreach ($haystack as $v) { 
			if ($needle == $v) return true; 
			elseif (is_array($v)) return in_arrayr($needle, $v); 
        }
		
        return false; 
	}  
}
// ------------------------------------------------------------------------

/**
 * Enclose string
 * 
 * Encloses given string with defined element ( Ex. single/ double quotes )
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('enclose_string'))
{
	function enclose_string( $str, $element = '"' )
	{
		if( !empty( $str ) ) {
			return $element . trim( $str, $element ) . $element;
		}
		
		return false;
	}
}

// ------------------------------------------------------------------------

/**
 * Prepare DB Values for Insert/ Update
 * 
 * Convert empty value to NULL ( disregard numeric value 0 )
 *
 * @access	public
 * @return	string/ NULL
 */
if ( ! function_exists('prepare_db_values'))
{
	function prepare_db_values( $data )
	{
		if( is_array( $data ) ) {
			foreach( $data as $key => $val ) {
				$data[ $key ] = ( !empty( $val ) || ( isset( $val ) && is_numeric( $val ) ) ) ? $val : null;
			}
			
			return $data;
		}
		else {
			return !empty( $data ) ? $data : null;
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Format Datetime
 * 
 * Change format of passed date value to either DB or Datepicker readable
 *
 * @access	public
 * @return	string/ bool
 */
if ( ! function_exists('format_datetime'))
{
	function format_datetime( $value, $type = 'db' )
	{
		if( !empty( $value ) ) {
			switch( $type ) {
				// Datetimepicker
				case 'front 1' :
					return date( 'm/d/Y H:i', strtotime( $value ) ); // Ex. 03/25/2013 14:30
				case 'front 2' :
					return date( 'm/d/Y', strtotime( $value ) ); // Ex. 03/25/2013
				case 'date' :
					return date( 'F d, Y', strtotime( $value ) ); // Ex. September 25, 2013
				
				case 'myads' :
					return date( 'n/j/Y', strtotime( $value ) ); // Ex. September 25, 2013
				case 'm' : 
					return date( 'm', strtotime( $value ) );
				case 'y' : 
					return date( 'Y', strtotime( $value ) );
				case 'ymd' : 
					return date( 'Y-m-d', strtotime( $value ) );				
				// Database readable
				case 'db' :
				default :
					return date( 'Y-m-d H:i:s', strtotime( $value ) ); // Ex. 2013-12-22 01:30:00
			}
		}
		
		return false;
	}
}

// ------------------------------------------------------------------------------------------------------------

if( ! function_exists('get_image_properties') )
{
	/**
     * Get image properties
     *
     * A helper function that gets info about the file
     *
     * @access    public
     * @param    string
     * @return    mixed
     */            
    function get_image_properties($path = '', $return = FALSE)
    {
        // For now we require GD but we should
        // find a way to determine this using IM or NetPBM
        if ( ! file_exists($path))
        {
            return FALSE;                
        }
        
        $vals = @getimagesize($path);
        
        $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
        
        $mime = (isset($types[$vals['2']])) ? 'image/'.$types[$vals['2']] : 'image/jpg';
                
		$v['width']         = $vals['0'];
		$v['height']        = $vals['1'];
		$v['image_type']    = $vals['2'];
		$v['size_str']      = $vals['3'];
		$v['mime_type']     = $mime;
		
		return $v;
    } 
}

// ------------------------------------------------------------------------------------------------------------

if( ! function_exists('parse_generic_color') )
{
	/**
     * Get Generic Color from string
     *
     * @access    public
     * @param    string
     * @return    string
     */       
	function parse_generic_color( $str = '' )
	{
		$generic_colors = array(
			'red' 			=> 'red',
			'darkred' 		=> 'darkred',
			'dark red' 		=> 'darkred',
			'blue' 			=> 'blue',
			'green' 		=> 'green',
			'yellow'		=> 'yellow',
			'orange' 		=> 'orange',
			'yelloworange' 	=> 'yelloworange',
			'yellow orange' => 'yelloworange',
			'purple' 		=> 'purple',
			'violet' 		=> 'purple',
			'plum'			=> 'darkviolet',
			'darkviolet'	=> 'darkviolet',
			'dark violet'	=> 'darkviolet',
			'lavender' 		=> 'lavender',
			'silver' 		=> 'silver',
			'metallic' 		=> 'silver',
			'light silver' 	=> 'lightsilver',
			'steel' 		=> 'steel',
			'smoke' 		=> 'steel',
			'lithium'		=> 'silver',
			'titanium'		=> 'silver',
			'gray' 			=> 'grey',
			'grey' 			=> 'grey',
			'black' 		=> 'black',
			'white' 		=> 'white',
			'pearl' 		=> 'pearl',
			'vanilla'		=> 'white',
			'brown' 		=> 'brown',
			'beige' 		=> 'beige',
			'sand'			=> 'beige',
			'cream'			=> 'beige',
			'navy'			=> 'navy',
			'mist'			=> 'lightblue',
			'light blue'	=> 'lightblue',
			'cosmic blue'	=> 'lightblue',
			'sky blue'		=> 'lightblue',
			'cumulus'		=> 'lightblue',
			'darkblue'		=> 'darkblue',
			'dark blue'		=> 'darkblue',
			'meteor'		=> 'charcoal',
			'charcoal'		=> 'charcoal',
			'granite'		=> 'charcoal',
			'magenta'		=> 'magenta',
			'burgundy'		=> 'maroon',
			'maroon'		=> 'maroon',
			'apple'			=> 'applegreen',
			'applegreen'	=> 'applegreen',
			'bronze'		=> 'bronze',
			'fog'			=> 'fog',
			'copper'		=> 'copper',
			'darkbrown'		=> 'darkbrown',
			'dark brown'	=> 'darkbrown',
			'darkgrey'		=> 'darkgray',
			'darkgray'		=> 'darkgray',
			'dark grey'		=> 'darkgray',
			'dark gray'		=> 'darkgray',
			'darkviolet'	=> 'darkviolet',
			'dark violet'	=> 'darkviolet',
			'darkyellow'	=> 'darkyellow',
			'dark yellow'	=> 'darkyellow',
			'forest'		=> 'forestgreen',
			'gold'			=> 'gold',
			'rose'			=> 'rose',
			'lemon'			=> 'lemon',
			'hot pink'		=> 'hotpink',
			'hotpink'		=> 'hotpink',
			'cherry'		=> 'hotpink',
			'pink'			=> 'pink',
			'carnation'		=> 'pink'
		);
		
		if( !empty( $str ) ) {
			$matches = array();
			
			preg_match( '/('.implode( '|', array_keys( $generic_colors ) ).')/i', $str, $matches );
			
			if( !empty( $matches ) ) {
				return $generic_colors[ strtolower( $matches[0] ) ];
			}
		}
		
		return false;
	}
}

// ------------------------------------------------------------------------------------------------------------

/**
 * Move array key to bottom
 *
 */
if( ! function_exists('move_to_bottom') )
{
	function move_to_bottom( &$array, $key ) 
	{
		if( array_key_exists( $key, $array ) ) {
			$value = $array[$key];
			unset($array[$key]);
			$array[$key] = $value;
		}
		else {
			return false;
		}
	}
}

// ------------------------------------------------------------------------------------------------------------

/**
 * Convert array indexing to specified key param
 * 
 */
if( ! function_exists('assoc_by') )
{
	function assoc_by($key, $array) {
		$new = array();
		foreach ($array as $v) {
			if (!array_key_exists($v[$key], $new)) {
				$new[$v[$key]] = $v;
			}
		}
		return $new;
	}
}

// ------------------------------------------------------------------------------------------------------------

/**
 * Pad ID to Directory structure 000/000/000 with respect to ID
 * 
 */
if( ! function_exists('pad_dir') )
{
	function pad_dir( $id )
	{
		if( !empty( $id ) ) {
			$padded_id 	= str_pad( $id, 9, 0, STR_PAD_LEFT );
			$split_id	= str_split( $padded_id, 3 );
			
			return ( $split_id[0] . '/' . $split_id[1] . '/' . $split_id[2] . '/' );
		}
	}
}

// ------------------------------------------------------------------------------------------------------------

/**
 * Truncate string and add ellipsis 
 * 
 */
if( ! function_exists('limit_str') )
{
	function limit_str( $string, $limit = 150, $suffix = 'ellipsis' ) 
	{
		//dont mess with string if it is smaller than limit!
		if ( strlen($string) <= $limit ) return $string;
		
		switch( $suffix ) {
		
			case 'ellipsis':
			default : 
				$suffix = '...';
		}
		
		return substr($string, 0, $limit) . $suffix;
	}
}

// ------------------------------------------------------------------------------------------------------------

/**
 * Shuffle preserve key
 *
 */
if( ! function_exists('shuffle_assoc') )
{
	function shuffle_assoc( $arr )
	{
		if( !is_array( $arr ) ) return $arr; 

		$keys = array_keys( $arr ); 
		shuffle( $keys ); 
		
		$random = array(); 

		foreach ($keys as $key) { 
			$random[] = $arr[ $key ]; 
		}

		return $random; 
	}

}


/**
 * 1 dimentional array to string
 * 
 * Returns string array
 *
 * @access	public
 * @return array
 */
if ( ! function_exists('ARRtoSTR'))
{
	function ARRtoSTR($arr = array(), $separator = ',')
	{	
		$result = '';

		if (is_array($arr)) {
			if(count($arr) > 1){
				$result = implode($separator, $arr);
			}else{
				if(count($arr) != 0){

					$result	 = $arr[0];
				}else{
					$result = '';
				}
			}
		}

		return $result;
	}
}

if (! function_exists('generate_password')){

	function generate_password($length){
      $pass_range = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
      $password = substr(str_shuffle($pass_range), 0, $length);
      return $password;
	}
}


if (! function_exists('send_email')){

	function send_email($email_to = "", $subject = "", $message = "", $alias = "Support"){
		$CI =& get_instance();
		$CI->load->library('email'); // load the library 
		
		$CI->email->clear();
 
	  	$CI->email->from('btmgsupp@gmail.com', $alias);
	  	$CI->email->to($email_to);
	  	$CI->email->subject($subject);

	  	$CI->email->message($message);
	   
		if($CI->email->send()) {
			return TRUE;
		} else {
			die();
			return FALSE;
		}	

	}
}

if (! function_exists('formatcurrency')){
function formatcurrency($floatcurr, $curr = "USD"){
        $currencies['ARS'] = array(2,',','.');          //  Argentine Peso
        $currencies['AMD'] = array(2,'.',',');          //  Armenian Dram
        $currencies['AWG'] = array(2,'.',',');          //  Aruban Guilder
        $currencies['AUD'] = array(2,'.',' ');          //  Australian Dollar
        $currencies['BSD'] = array(2,'.',',');          //  Bahamian Dollar
        $currencies['BHD'] = array(3,'.',',');          //  Bahraini Dinar
        $currencies['BDT'] = array(2,'.',',');          //  Bangladesh, Taka
        $currencies['BZD'] = array(2,'.',',');          //  Belize Dollar
        $currencies['BMD'] = array(2,'.',',');          //  Bermudian Dollar
        $currencies['BOB'] = array(2,'.',',');          //  Bolivia, Boliviano
        $currencies['BAM'] = array(2,'.',',');          //  Bosnia and Herzegovina, Convertible Marks
        $currencies['BWP'] = array(2,'.',',');          //  Botswana, Pula
        $currencies['BRL'] = array(2,',','.');          //  Brazilian Real
        $currencies['BND'] = array(2,'.',',');          //  Brunei Dollar
        $currencies['CAD'] = array(2,'.',',');          //  Canadian Dollar
        $currencies['KYD'] = array(2,'.',',');          //  Cayman Islands Dollar
        $currencies['CLP'] = array(0,'','.');           //  Chilean Peso
        $currencies['CNY'] = array(2,'.',',');          //  China Yuan Renminbi
        $currencies['COP'] = array(2,',','.');          //  Colombian Peso
        $currencies['CRC'] = array(2,',','.');          //  Costa Rican Colon
        $currencies['HRK'] = array(2,',','.');          //  Croatian Kuna
        $currencies['CUC'] = array(2,'.',',');          //  Cuban Convertible Peso
        $currencies['CUP'] = array(2,'.',',');          //  Cuban Peso
        $currencies['CYP'] = array(2,'.',',');          //  Cyprus Pound
        $currencies['CZK'] = array(2,'.',',');          //  Czech Koruna
        $currencies['DKK'] = array(2,',','.');          //  Danish Krone
        $currencies['DOP'] = array(2,'.',',');          //  Dominican Peso
        $currencies['XCD'] = array(2,'.',',');          //  East Caribbean Dollar
        $currencies['EGP'] = array(2,'.',',');          //  Egyptian Pound
        $currencies['SVC'] = array(2,'.',',');          //  El Salvador Colon
        $currencies['ATS'] = array(2,',','.');          //  Euro
        $currencies['BEF'] = array(2,',','.');          //  Euro
        $currencies['DEM'] = array(2,',','.');          //  Euro
        $currencies['EEK'] = array(2,',','.');          //  Euro
        $currencies['ESP'] = array(2,',','.');          //  Euro
        $currencies['EUR'] = array(2,',','.');          //  Euro
        $currencies['FIM'] = array(2,',','.');          //  Euro
        $currencies['FRF'] = array(2,',','.');          //  Euro
        $currencies['GRD'] = array(2,',','.');          //  Euro
        $currencies['IEP'] = array(2,',','.');          //  Euro
        $currencies['ITL'] = array(2,',','.');          //  Euro
        $currencies['LUF'] = array(2,',','.');          //  Euro
        $currencies['NLG'] = array(2,',','.');          //  Euro
        $currencies['PTE'] = array(2,',','.');          //  Euro
        $currencies['GHC'] = array(2,'.',',');          //  Ghana, Cedi
        $currencies['GIP'] = array(2,'.',',');          //  Gibraltar Pound
        $currencies['GTQ'] = array(2,'.',',');          //  Guatemala, Quetzal
        $currencies['HNL'] = array(2,'.',',');          //  Honduras, Lempira
        $currencies['HKD'] = array(2,'.',',');          //  Hong Kong Dollar
        $currencies['HUF'] = array(0,'','.');           //  Hungary, Forint
        $currencies['ISK'] = array(0,'','.');           //  Iceland Krona
        $currencies['INR'] = array(2,'.',',');          //  Indian Rupee
        $currencies['IDR'] = array(2,',','.');          //  Indonesia, Rupiah
        $currencies['IRR'] = array(2,'.',',');          //  Iranian Rial
        $currencies['JMD'] = array(2,'.',',');          //  Jamaican Dollar
        $currencies['JPY'] = array(0,'',',');           //  Japan, Yen
        $currencies['JOD'] = array(3,'.',',');          //  Jordanian Dinar
        $currencies['KES'] = array(2,'.',',');          //  Kenyan Shilling
        $currencies['KWD'] = array(3,'.',',');          //  Kuwaiti Dinar
        $currencies['LVL'] = array(2,'.',',');          //  Latvian Lats
        $currencies['LBP'] = array(0,'',' ');           //  Lebanese Pound
        $currencies['LTL'] = array(2,',',' ');          //  Lithuanian Litas
        $currencies['MKD'] = array(2,'.',',');          //  Macedonia, Denar
        $currencies['MYR'] = array(2,'.',',');          //  Malaysian Ringgit
        $currencies['MTL'] = array(2,'.',',');          //  Maltese Lira
        $currencies['MUR'] = array(0,'',',');           //  Mauritius Rupee
        $currencies['MXN'] = array(2,'.',',');          //  Mexican Peso
        $currencies['MZM'] = array(2,',','.');          //  Mozambique Metical
        $currencies['NPR'] = array(2,'.',',');          //  Nepalese Rupee
        $currencies['ANG'] = array(2,'.',',');          //  Netherlands Antillian Guilder
        $currencies['ILS'] = array(2,'.',',');          //  New Israeli Shekel
        $currencies['TRY'] = array(2,'.',',');          //  New Turkish Lira
        $currencies['NZD'] = array(2,'.',',');          //  New Zealand Dollar
        $currencies['NOK'] = array(2,',','.');          //  Norwegian Krone
        $currencies['PKR'] = array(2,'.',',');          //  Pakistan Rupee
        $currencies['PEN'] = array(2,'.',',');          //  Peru, Nuevo Sol
        $currencies['UYU'] = array(2,',','.');          //  Peso Uruguayo
        $currencies['PHP'] = array(2,'.',',');          //  Philippine Peso
        $currencies['PLN'] = array(2,'.',' ');          //  Poland, Zloty
        $currencies['GBP'] = array(2,'.',',');          //  Pound Sterling
        $currencies['OMR'] = array(3,'.',',');          //  Rial Omani
        $currencies['RON'] = array(2,',','.');          //  Romania, New Leu
        $currencies['ROL'] = array(2,',','.');          //  Romania, Old Leu
        $currencies['RUB'] = array(2,',','.');          //  Russian Ruble
        $currencies['SAR'] = array(2,'.',',');          //  Saudi Riyal
        $currencies['SGD'] = array(2,'.',',');          //  Singapore Dollar
        $currencies['SKK'] = array(2,',',' ');          //  Slovak Koruna
        $currencies['SIT'] = array(2,',','.');          //  Slovenia, Tolar
        $currencies['ZAR'] = array(2,'.',' ');          //  South Africa, Rand
        $currencies['KRW'] = array(0,'',',');           //  South Korea, Won
        $currencies['SZL'] = array(2,'.',', ');         //  Swaziland, Lilangeni
        $currencies['SEK'] = array(2,',','.');          //  Swedish Krona
        $currencies['CHF'] = array(2,'.','\'');         //  Swiss Franc 
        $currencies['TZS'] = array(2,'.',',');          //  Tanzanian Shilling
        $currencies['THB'] = array(2,'.',',');          //  Thailand, Baht
        $currencies['TOP'] = array(2,'.',',');          //  Tonga, Paanga
        $currencies['AED'] = array(2,'.',',');          //  UAE Dirham
        $currencies['UAH'] = array(2,',',' ');          //  Ukraine, Hryvnia
        $currencies['USD'] = array(2,'.',',');          //  US Dollar
        $currencies['VUV'] = array(0,'',',');           //  Vanuatu, Vatu
        $currencies['VEF'] = array(2,',','.');          //  Venezuela Bolivares Fuertes
        $currencies['VEB'] = array(2,',','.');          //  Venezuela, Bolivar
        $currencies['VND'] = array(0,'','.');           //  Viet Nam, Dong
        $currencies['ZWD'] = array(2,'.',' ');          //  Zimbabwe Dollar

        function formatinr($input){
            //CUSTOM FUNCTION TO GENERATE ##,##,###.##
            $dec = "";
            $pos = strpos($input, ".");
            if ($pos === false){
                //no decimals   
            } else {
                //decimals
                $dec = substr(round(substr($input,$pos),2),1);
                $input = substr($input,0,$pos);
            }
            $num = substr($input,-3); //get the last 3 digits
            $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
            while(strlen($input) > 0) //loop the process - further get digits 2 by 2
            {
                $num = substr($input,-2).",".$num;
                $input = substr($input,0,-2);
            }
            return $num . $dec;
        }


        if ($curr == "INR"){    
            return formatinr($floatcurr);
        } else {
            return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]);
        }
    }
}

   
if ( ! defined( 'PREFIX' )) {
	define( 'PREFIX', 'jfctbl_' );
}

if (!defined('CallAPI')){
	// Method: POST, PUT, GET etc
	// Data: array("param" => "value") ==> index.php?param=value
	function CallAPI($method, $url, $data = false){
	    $curl = curl_init();

	    switch ($method)
	    {
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    // Optional Authentication:
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}

}
