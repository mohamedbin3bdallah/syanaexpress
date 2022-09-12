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
//$route['default_controller'] = 'Login';
$route['default_controller'] = 'Website';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['Login']='Login';
$route['Admin/login']='Admin/login';
$route['Signup']='Login/signup';
$route['forgotpassword']='Login/forgotpassword';
$route['pay']='payment/makePayment';
$route['payment_feedback']='payment/receivePayment';
$route['payment_failure']='payment/receivePaymentFail';

$route['404_override'] = 'Website/page404';
$route['404']='Website/page404';

$route['/']='Website/index/en';
$route['en']='Website/index/en';
$route['ar']='Website/index/ar';
//$route['(:any)']='Website/index/$1';
$route['(:any)/subscribe_post']='Website/subscribe_post/$1';

$route['(:any)/login']='Website/login/$1';
$route['(:any)/login_post']='Website/login_post/$1';

$route['(:any)/hero']='Website/hero/$1';
$route['(:any)/hero_post']='Website/hero_post/$1';

$route['(:any)/register/(:num)']='Website/register/$1/$2';
$route['(:any)/register_post']='Website/register_post/$1';

$route['(:any)/categories']='Website/categories/$1';

$route['(:any)/contact']='Website/contact/$1';
$route['(:any)/contact_post']='Website/contact_post/$1';