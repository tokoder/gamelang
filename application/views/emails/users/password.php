<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * English version (Required).
 */
$messages['english'] = <<<EOT
Hello {name},

This email confirms that your password at {site_anchor} has been successfully changed. You may now <a href="{login_url}" target="_blank">login</a> using the new one.

If you did not perform this action, please contact us as quick as possible to resolve this issue.

This action was performed from this IP address: {ip_link}.

Kind regards,
-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Email ini mengonfirmasi bahwa kata sandi Anda di {site_anchor} telah berhasil diubah. Anda sekarang dapat <a href="{login_url}" target="_blank">login</a> menggunakan yang baru.

Jika Anda tidak melakukan tindakan ini, harap hubungi kami secepat mungkin untuk menyelesaikan masalah ini.

Tindakan ini dilakukan dari alamat IP ini: {ip_link}.

Salam Hormat,
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
echo apply_filters('email_users_password_changed', $message, $lang);
