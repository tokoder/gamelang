<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Captcha Configuration
| -------------------------------------------------------------------
*/

// Images path and URL.
$config['img_path']    = APPPATH.'cache/captcha/';
$config['img_url']     = base_url('resource/cache/captcha');

// Catpcha font path, font size, word length and characters used.
$config['font_path']   = apply_filters('captcha_font_path', APPPATH.'cache/captcha/fonts/edmunds.ttf');
$config['font_size']   = apply_filters('captcha_font_size', 20);
$config['word_length'] = apply_filters('captcha_word_length', 3);
$config['pool']        = apply_filters('captcha_pool', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

// Captcha image dimensions and ID.
$config['img_width']   = apply_filters('captcha_img_width', 120);
$config['img_height']  = apply_filters('captcha_img_height', 40);
$config['img_id']      = apply_filters('captcha_img_id', 'captcha');

// Captcha expiration time.
$config['expiration']  = (MINUTE_IN_SECONDS * 5);

// Different elements colors.
$config['colors'] = array(
	'background' => apply_filters('captcha_background_color', array(233, 236, 239)),
	'border'     => apply_filters('captcha_border_color',     array(233, 236, 239)),
	'text'       => apply_filters('captcha_text_color',       array(0, 0, 0)),
	'grid'       => apply_filters('captcha_grid_color',       array(233, 236, 239)),
);