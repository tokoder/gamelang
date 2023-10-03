<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * English version (Required).
 */
$messages['english'] = <<<EOT
Hello {name},

Your account at {site_anchor} was successfully activated. You may now <a href="{login_url}" target="_blank">login</a> anytime you want.

-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Akun Anda di {site_anchor} berhasil diaktifkan. Anda sekarang dapat <a href="{login_url}" target="_blank">login</a> kapan saja Anda mau.

-- Tim {site_name}.
EOT;

// ------------------------------------------------------------------------

/**
 * We make sure to use the correct translation if found.
 * Otherwise, we fall-back to English.
 */
$lang    = $this->lang->lang_detail('folder');
$message = isset($messages[$lang]) ? $messages[$lang] : $messages['english'];

/**
 * Filters the welcome email message.
 */
echo  apply_filters('email_users_activated', $message, $lang);
