<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'dashboardcontroller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//MIGRATE ROUTE
$route['migrate'] = 'migratecontroller';

//WEB ROUTES

$route['dashboard'] = 'dashboardcontroller';

$route['auth/login']['GET'] = 'authcontroller';
$route['auth/login']['POST'] = 'authcontroller/login';
$route['auth/logout']['GET'] = 'authcontroller/logout';

$route['recuperar']['GET'] = 'resetpasscontroller';
$route['recuperar']['POST'] = 'resetpasscontroller/sendemail';
$route['recuperar/(:any)']['GET'] = 'resetpasscontroller/showResetPass';
$route['recuperar/(:any)']['POST'] = 'resetpasscontroller/resetPass/$1';

$route['meusdados']['GET'] = 'usuariocontroller/myData';
$route['meusdados']['POST'] = 'usuariocontroller/updateMyData';

$route['usuarios']['GET'] = 'usuariocontroller';
$route['usuarios/(:num)']['GET'] = 'usuariocontroller';

$route['usuarios/novo']['GET'] = 'usuariocontroller/create';
$route['usuarios/novo']['POST'] = 'usuariocontroller/store';

$route['usuarios/update/(:num)']['GET'] = 'usuariocontroller/show/$1';
$route['usuarios/update/(:num)']['POST'] = 'usuariocontroller/update/$1';

$route['usuarios/delete/(:num)']['GET'] = 'usuariocontroller/destroy/$1';

//API ROUTES

$route['api/auth/login']['POST'] = 'api/apiauthcontroller/login';
$route['api/auth/logout']['POST'] = 'api/apiauthcontroller/logout';

$route['api/meusdados']['GET'] = 'api/apiusuariocontroller/myData';
$route['api/meusdados']['POST'] = 'api/apiusuariocontroller/updateMyData';

$route['api/recuperar']['POST'] = 'api/apiresetpasscontroller/sendemail';
$route['api/recuperar/(:any)']['POST'] = 'api/apiresetpasscontroller/resetPass/$1';

$route['api/usuarios']['GET'] = 'api/apiusuariocontroller/index';
$route['api/usuarios/(:num)']['GET'] = 'api/apiusuariocontroller/index';

$route['api/usuarios/show/(:num)']['GET'] = 'api/apiusuariocontroller/show/$1';
$route['api/usuarios/novo']['POST'] = 'api/apiusuariocontroller/store';
$route['api/usuarios/update/(:num)']['POST'] = 'api/apiusuariocontroller/update/$1';
$route['api/usuarios/delete/(:num)']['POST'] = 'api/apiusuariocontroller/destroy/$1';
