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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['/'] = 'welcome/index';

$route['admin/user'] = 'user/index';
$route['admin/user/(:any)'] = 'user/$1';
$route['admin/user/(:any)/(:num)'] = 'user/$1/$2';

$route['admin/job'] = 'pekerjaan/index';
$route['admin/job/(:any)'] = 'pekerjaan/$1';
$route['admin/job/(:any)/(:num)'] = 'pekerjaan/$1/$2';

$route['admin/kav'] = 'kavling/index';
$route['admin/kav/(:any)'] = 'kavling/$1';
$route['admin/kav/(:any)/(:num)'] = 'kavling/$1/$2';

$route['admin/report/(:any)'] = 'laporan/$1';
$route['admin/report/(:any)/(:any)'] = 'laporan/$1/$2';
$route['admin/report/(:any)/(:any)/(:any)'] = 'laporan/$1/$2/$3';
$route['admin/report/(:any)/(:any)/(:any)/(:any)'] = 'laporan/$1/$2/$3/$4';

$route['mandor/profile/(:num)'] = 'mandor/profile/$1';

$route['mandor/(:any)'] = 'laporan/$1';
$route['mandor/(:any)/(:any)'] = 'laporan/$1/$2';
$route['mandor/(:any)/(:any)/(:any)'] = 'laporan/$1/$2/$3';
$route['mandor/(:any)/(:any)/(:any)/(:any)'] = 'laporan/$1/$2/$3/$4';



