<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route["default_controller"]    = "admin/Dashboard";

$route["transactions/qr-code/(:any)"] = "admin/Qr_code/index/$1";

$route["transactions/cash-in"]          = "admin/Transactions/cash_in";
$route["transactions/cash-in/(:num)"]   = "admin/Transactions/cash_in/$1";

$route["transactions/cash-out"]         = "admin/Transactions/cash_out";
$route["transactions/cash-out/(:num)"]  = "admin/Transactions/cash_out/$1";

$route["marketplace/products"]          = "admin/Marketplace/products";
$route["marketplace/products/(:num)"]   = "admin/Marketplace/products/$1";

$route["marketplace/products/new"]                  = "admin/Marketplace/product_new";
$route["marketplace/products/update/(:num)"]        = "admin/Marketplace/product_update/$1";

$route["marketplace/products/confirmation-remove/image-(:any)"] = "admin/Marketplace/confirmation_remove_product_image/$1";

$route["login"]                 = "public/Login";
$route["logout"]                = "public/Logout";

$route['404_override'] = 'public/Error_404';
$route['translate_uri_dashes'] = FALSE;

























