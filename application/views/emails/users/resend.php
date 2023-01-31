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

You have recently requested a new activation link on {site_anchor} because your account was not active.
To activate your account click on the following link or copy-paste it in your browser:
{link}

If you did not request this, no further action is required.

This action was requested from this IP address: {ip_link}.

Very kind regards,
-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Anda baru saja meminta tautan aktivasi baru di {site_anchor} karena akun Anda tidak aktif.
Untuk mengaktifkan akun Anda, klik tautan berikut atau salin-tempel di browser Anda:
{link}

Jika Anda tidak memintanya, tidak diperlukan tindakan lebih lanjut.

Tindakan ini diminta dari alamat IP ini: {ip_link}.

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
echo  apply_filters('email_users_resend_activation', $message, $lang);
