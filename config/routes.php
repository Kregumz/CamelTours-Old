<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

// Defaults Routes
$route['default_controller'] = 'home';
$route['404_override'] = 'error_404';
// Public Form Paths
$route['login/attempt-login'] = 'login/attempt_login';
$route['forgot/send-form'] = 'forgot/send_form';
$route['contact/send-form'] = 'contact/send_form';
$route['signup/send-form'] = 'signup/send_form';
// CMS Home
$route['cms/home/(:num)'] = 'cms/home/index/$1';
// Add Tour
$route['cms/add-tour'] = 'cms/add_tour';
$route['cms/add-tour/send-form'] = 'cms/add_tour/send_form';
// Add Node
$route['cms/add-node'] = 'cms/add_node';
$route['cms/add-node/(:num)'] = 'cms/add_node/index/$1';
$route['cms/add-node/send-form'] = 'cms/add_node/send_form';
// Account Settings
$route['cms/account-settings'] = 'cms/account_settings';
$route['cms/account-settings/send-form'] = 'cms/account_settings/send_form';
// Tour Settings
$route['cms/tour-settings'] = 'cms/tour_settings';
$route['cms/tour-settings/(:num)'] = 'cms/tour_settings/index/$1';
$route['cms/tour-settings/(:num)/send-form'] = 'cms/tour_settings/send_form/$1';
$route['cms/tour-settings/(:num)/download-zip'] = 'cms/tour_settings/download_zip/$1';
// Node Settings
$route['cms/node-settings'] = 'cms/node_settings';
$route['cms/node-settings/(:num)/(:num)'] = 'cms/node_settings/index/$1/$2';
$route['cms/node-settings/(:num)/(:num)/send-form'] = 'cms/node_settings/send_form/$1/$2';
// Node Details Page
$route['cms/node/(:num)/(:num)'] = 'cms/node/index/$1/$2';
$route['cms/node/(:num)/(:num)/send-form'] = 'cms/node/send_form/$1/$2';
// Slide Details Page
$route['cms/slide/(:num)/(:num)/(:num)'] = 'cms/slide/index/$1/$2/$3';
$route['cms/slide/(:num)/(:num)/(:num)/send-form'] = 'cms/slide/send_form/$1/$2/$3';

/* End of file routes.php */
/* Location: ./application/config/routes.php */