<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * English version (Required).
 */
$messages['english'] = <<<EOT
Hello {name},

Thank you for registering at {site_anchor}. Your account is created and must be activated before you can use it.

To activate your account click on the following link or copy-paste it in your browser:
{link}

Very kind regards,
-- {site_name} Team.
EOT;

/**
 * Indonesia version
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Terima kasih telah mendaftar di {site_anchor}. Akun Anda dibuat dan harus diaktifkan sebelum Anda dapat menggunakannya.

Untuk mengaktifkan akun Anda, klik tautan berikut atau salin-tempel di browser Anda:
{link}

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
echo  apply_filters('email_users_register', $message, $lang);
