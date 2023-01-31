<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
*/
$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://".$_SERVER['HTTP_HOST'];
$config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
*/
$config['language']	= 'indonesia';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
*/
$config['enable_hooks'] = TRUE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
*/
$config['subclass_prefix'] = 'CG_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
*/
$config['composer_autoload'] = TRUE;

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
*/
$config['log_threshold'] = [1];

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/
$encryption_path_dist = APPPATH.'config/gamelang/encryption_key.php.dist';
$encryption_path = APPPATH.'config/gamelang/encryption_key.php';
if ( ! file_exists($encryption_path))
{
	$encryption_file = fopen($encryption_path, 'w');
	$encryption_key = uniqid().uniqid().uniqid().uniqid();

	$content = file_get_contents($encryption_path_dist);
	$content = str_replace('{KEY}', $encryption_key, $content);

	fwrite($encryption_file, $content);
	fclose($encryption_file);
}

$config['encryption_key'] = '';
include_once($encryption_path);

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
*/
$config['sess_driver']             = 'files';
$config['sess_cookie_name']        = 'ci_session';
$config['sess_samesite']           = 'Lax';
$config['sess_expiration']         = 7200;
$config['sess_save_path']          = sys_get_temp_dir();
$config['sess_match_ip']           = FALSE;
$config['sess_time_to_update']     = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
*/
$config['csrf_protection']   = (ENVIRONMENT !== 'development');
$config['csrf_token_name']   = 'csrf_codeigniter_gamelang';
$config['csrf_cookie_name']  = 'csrf_cookie_codeigniter_gamelang';
$config['csrf_expire']       = 7200;
$config['csrf_regenerate']   = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
*/
if ( function_exists( 'date_default_timezone_set' ) ) {
    date_default_timezone_set('Asia/Jakarta');
}
$config['time_reference'] = 'Asia/Jakarta';