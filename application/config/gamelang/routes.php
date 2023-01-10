<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Page Missing ROUTES
| -------------------------------------------------------------------------
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
*/
$route['404_override'] = 'welcome/error_404';
$route['translate_uri_dashes'] = TRUE;

/*
| -------------------------------------------------------------------------
| Authentication routes.
| -------------------------------------------------------------------------
*/
Route::any('logout', 'auth/logout', array('as' => 'logout'));
Route::any('login', 'auth/login', array('as' => 'login'), function() {
	Route::any('reset', 'auth/reset', array('as' => 'reset-password'));
	Route::any('forgot', 'auth/forgot', array('as' => 'lost-password'));
	Route::any('restore', 'auth/restore', array('as' => 'restore-account'));
});

/*
| -------------------------------------------------------------------------
| Account creation routes.
| -------------------------------------------------------------------------
*/
Route::any('register', 'auth/register', array('as' => 'register'), function() {
	Route::any('resend', 'auth/resend', array('as' => 'resend-link'));
	Route::any('activate', 'auth/activate', array('as' => 'activate-account'));
});

/*
| -------------------------------------------------------------------------
| administration panel
| -------------------------------------------------------------------------
| The application has a built-in administration panel.
| Each package can have context controllers.
*/
Route::prefix(config_item('site_admin'), function()
{
	// Reserved back-end contexts.
	global $back_contexts;
	$back_routes = implode('|', $back_contexts);
	Route::context("({$back_routes})", '$1', array(
		'home'   => '$1/index',
		'offset' => 1,
	));
});

/*
| -------------------------------------------------------------------------
| Front-end context.
| -------------------------------------------------------------------------
*/
global $front_contexts;
$front_routes = implode('|', $front_contexts);
Route::context("({$front_routes})", '$1', array(
	'home'   => '$1/index',
	'offset' => 1,
));