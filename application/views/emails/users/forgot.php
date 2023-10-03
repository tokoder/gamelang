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

You are receiving this email because we received a password reset request for your account on {site_anchor}.

Click on the following link or copy-paste it in your browser if you wish to proceed:
{link}

If you did not request a password reset, no further action is required.

This action was requested from this IP address: {ip_link}.

Kind regards,
-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda di {site_anchor}.

Klik tautan berikut atau salin-tempel di browser Anda jika Anda ingin melanjutkan:
{link}

Jika Anda tidak meminta pengaturan ulang kata sandi, tidak diperlukan tindakan lebih lanjut.

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
echo apply_filters('email_users_lost_password', $message, $lang);
