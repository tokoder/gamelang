<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Input Configuration
| -------------------------------------------------------------------
*/

// Username field.
$config['username'] = array(
	'name'        => 'username',
	'id'          => 'username',
	'placeholder' => 'lang:lang_username',
);

// Identity field.
$config['identity'] = array(
	'name'        => 'identity',
	'id'          => 'identity',
	'placeholder' => 'lang:lang_identity',
);

// ------------------------------------------------------------------------
// Passwords fields.
// ------------------------------------------------------------------------

// Password field.
$config['password'] = array(
	'type'        => 'password',
	'name'        => 'password',
	'id'          => 'password',
	'placeholder' => 'lang:lang_password',
);

// Confirm field.
$config['cpassword'] = array(
	'type'        => 'password',
	'name'        => 'cpassword',
	'id'          => 'cpassword',
	'placeholder' => 'lang:confirm_password',
);

// New password field.
$config['npassword'] = array(
	'type'        => 'password',
	'name'        => 'npassword',
	'id'          => 'npassword',
	'placeholder' => 'lang:lang_new_password',
);

// Current password field.
$config['opassword'] = array(
	'type'        => 'password',
	'name'        => 'opassword',
	'id'          => 'opassword',
	'placeholder' => 'lang:lang_current_password',
);

// ------------------------------------------------------------------------
// Email addresses fields.
// ------------------------------------------------------------------------

// Email field.
$config['email'] = array(
	'type'        => 'email',
	'name'        => 'email',
	'id'          => 'email',
	'placeholder' => 'lang:lang_email_address',
);

// New email field.
$config['nemail'] = array(
	'type'        => 'email',
	'name'        => 'nemail',
	'id'          => 'nemail',
	'placeholder' => 'lang:lang_new_email_address',
);

// ------------------------------------------------------------------------
// User profile fields.
// ------------------------------------------------------------------------

// First name field.
$config['first_name'] = array(
	'name'        => 'first_name',
	'id'          => 'first_name',
	'placeholder' => 'lang:lang_first_name',
);

// Last name field.
$config['last_name'] = array(
	'name'        => 'last_name',
	'id'          => 'last_name',
	'placeholder' => 'lang:lang_last_name',
);

// Gender field.
$config['gender'] = array(
	'type' => 'dropdown',
	'name' => 'gender',
	'id'   => 'gender',
	'options' => array(
		''       => 'lang:lang_unspecified',
		'male'   => 'lang:lang_male',
		'female' => 'lang:lang_female',
	),
);

// Company field.
$config['company'] = array(
	'name'        => 'company',
	'id'          => 'company',
	'placeholder' => 'lang:lang_company',
);

// Phone field.
$config['phone'] = array(
	'name'        => 'phone',
	'id'          => 'phone',
	'placeholder' => 'lang:lang_phone',
);

// Location field.
$config['location'] = array(
	'name'        => 'location',
	'id'          => 'location',
	'placeholder' => 'lang:lang_location',
);

// ------------------------------------------------------------------------
// Fields used by groups and objects.
// ------------------------------------------------------------------------

// Name fields (form groups and objects).
$config['name'] = array(
	'name'        => 'name',
	'id'          => 'name',
	'placeholder' => 'lang:lang_name',
);

// Title (same as name field).
$config['title'] = array(
	'name'        => 'name',
	'id'          => 'name',
	'placeholder' => 'lang:lang_title',
);

// Elements slug.
$config['slug'] = array(
	'name'        => 'slug',
	'id'          => 'slug',
	'placeholder' => 'lang:lang_slug',
);

// Description field.
$config['description'] = array(
	'type'        => 'textarea',
	'name'        => 'description',
	'id'          => 'description',
	'rows' 		  => '5',
	'placeholder' => 'lang:lang_description',
);

// Used by menu items.
$config['href'] = array(
	'name'        => 'href',
	'id'          => 'href',
	'placeholder' => 'lang:lang_url',
);

// Menu order.
$config['order'] = array(
	'type'        => 'number',
	'name'        => 'order',
	'id'          => 'order',
	'placeholder' => 'lang:lang_order',
);

// Privacy.
$config['privacy'] = array(
	'type'    => 'dropdown',
	'name'    => 'privacy',
	'id'      => 'privacy',
	'options' => array(
		'0' => 'lang:lang_privacy_hidden',
		'1' => 'lang:lang_privacy_private',
		'2' => 'lang:lang_privacy_public',
	)
);

// ------------------------------------------------------------------------
// SEO Fields.
// ------------------------------------------------------------------------

// Meta title.
$config['meta_title'] = array(
	'name'        => 'meta_title',
	'id'          => 'meta_title',
	'placeholder' => 'lang:lang_meta_title',
	'maxlength'   => '70',
);

// Meta description
$config['meta_description'] = array(
	'name'        => 'meta_description',
	'id'          => 'meta_description',
	'placeholder' => 'lang:lang_meta_description',
	'maxlength'   => '160',
);

// Meta keywords.
$config['meta_keywords'] = array(
	'name'        => 'meta_keywords',
	'id'          => 'meta_keywords',
	'placeholder' => 'lang:lang_meta_keywords',
	'maxlength'   => '255',
);