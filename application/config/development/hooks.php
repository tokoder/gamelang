<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks MY App
| -------------------------------------------------------------------------
*/
$hook['pre_system'][] = array(
    'class'    => 'CG_app',
	'filename' => 'CG_app.php',
	'function' => 'init',
	'filepath' => 'hooks',
);

/*
| -------------------------------------------------------------------------
| Appropriate headers and redirection for SSL websites.
| -------------------------------------------------------------------------
*/
$hook['post_controller'][] = array(
    'class'    => 'CG_ssl',
	'filename' => 'CG_ssl.php',
	'function' => 'ssl_hook',
	'filepath' => 'hooks',
);