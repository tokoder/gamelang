<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| General settings.
|--------------------------------------------------------------------------
*/
$config['site_description'] = 'An open source codeigniter management system.';
$config['site_keywords']    = 'these, are, site, keywords';
$config['site_author']      = 'Tokoder Team';
$config['site_admin']   	= 'admin';
$config['site_name']        = 'CodeIgniter Gamelang';

/*
|--------------------------------------------------------------------------
| DATABASE settings.
|--------------------------------------------------------------------------
*/
$config['hostname'] = '';
$config['username'] = '';
$config['password'] = '';
$config['database'] = '';

/*
|--------------------------------------------------------------------------
| Packages settings.
|--------------------------------------------------------------------------
*/
$config['package_folder'] = 'packages';
$config['package_locations'] = array(
	APPPATH.$config['package_folder']
);

/*
|--------------------------------------------------------------------------
| Pagination settings.
|--------------------------------------------------------------------------
*/
$config['per_page'] = 10;

/*
|--------------------------------------------------------------------------
| Google analytics and verification.
|--------------------------------------------------------------------------
*/
$config['google_site_verification'] = '';
$config['google_analytics_id']      = '';

/*
|--------------------------------------------------------------------------
| Users settings.
|--------------------------------------------------------------------------
*/
$config['allow_multi_session'] = true;
$config['allow_registration']  = true;
$config['manual_activation']   = false;
$config['email_activation']    = false;
$config['use_gravatar']        = false;
$config['login_type']          = 'both';

/*
|--------------------------------------------------------------------------
| Email settings.
|--------------------------------------------------------------------------
*/
$config['contact_email'] = 'admin@localhost';
$config['mail_protocol'] = 'mail';
$config['sendmail_path'] = '/usr/sbin/sendmail';
$config['server_email']  = '';
$config['smtp_crypto']   = 'none';
$config['smtp_host']     = '';
$config['smtp_port']     = '';
$config['smtp_user']     = '';
$config['smtp_pass']     = '';

/*
|--------------------------------------------------------------------------
| Language settings.
|--------------------------------------------------------------------------
*/
$config['languages'] = array('indonesia');

/*
|--------------------------------------------------------------------------
| Themes settings.
|--------------------------------------------------------------------------
*/
$config['theme'] = 'default';
$config['theme_compress'] = (ENVIRONMENT !== 'development');

/*
|--------------------------------------------------------------------------
| Upload settings.
|--------------------------------------------------------------------------
*/
$config['allowed_types'] = 'gif|png|jpeg|jpg|pdf|doc|txt|docx|xls|zip|rar|xls|mp4';
$config['upload_path']   = 'uploads';
$config['max_height']    = 0;
$config['min_height']    = 0;
$config['max_width']     = 0;
$config['min_width']     = 0;
$config['max_size']      = 0;

/*
|--------------------------------------------------------------------------
| Password settings.
|--------------------------------------------------------------------------
*/
$config['password_hash_options'] = array();
$config['password_hash_algo']    = PASSWORD_BCRYPT;
$config['use_password_hash']     = true;
$config['hash']                  = 'sha256';
$config['max']                   = 13;
$config['min']                   = 5;

/*
|--------------------------------------------------------------------------
| Captcha settings.
|--------------------------------------------------------------------------
*/
$config['recaptcha_private_key'] = '';
$config['recaptcha_site_key']    = '';
$config['use_recaptcha']         = false;
$config['use_captcha']           = false;

/*
|--------------------------------------------------------------------------
| Date and time format.
|--------------------------------------------------------------------------
*/
$config['datetime_format'] = 'Y-m-d G:i:s';	// 1988-03-21 23:15:30
$config['date_format'] = 'd/m/Y'; 			// 21/03/1988
$config['time_format'] = 'G:i'; 			// 23:15

/*
|--------------------------------------------------------------------------
| Social network links sharers.
|--------------------------------------------------------------------------
*/
$config['share_urls'] = array(
	'email'      => 'mailto:?subject={title}&amp;body={description}:%0A{url}',
	'facebook'   => 'https://www.facebook.com/sharer.php?u={url}&amp;t={title}&amp;d={description}',
	'googleplus' => 'https://plus.google.com/share?url={url}',
	'linkedin'   => 'http://www.linkedin.com/shareArticle?mini=true&amp;url={url}&amp;title={title}&amp;summary={description}&amp;source={site_name}',
	'twitter'    => 'http://twitter.com/share?url={url}&amp;text={title}&amp;via={site_name}',
);
