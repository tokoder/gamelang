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

This email confirms that your account at {site_anchor} has been successfully restored.

Welcome back with us and we hope this time you will enjoy.

Kind regards,
-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Email ini mengonfirmasi bahwa akun Anda di {site_anchor} telah berhasil dipulihkan.

Selamat datang kembali bersama kami dan kami harap kali ini Anda akan menikmati.

Salam Hormat,
-- Tim {site_name}.
EOT;

/**
 * We make sure to use the correct translation if found.
 * Otherwise, we fall-back to English.
 */
$lang    = $this->lang->lang_detail('folder');
$message = isset($messages[$lang]) ? $messages[$lang] : $messages['english'];

/**
 * Filters the welcome email message.
 */
echo apply_filters('email_users_restore_account', $message, $lang);
