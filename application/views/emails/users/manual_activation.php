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

Thank you for joining us at {site_anchor}. Your account is created but needs approval by a site admin before being active.
We sincerely apologies for this crucial step, but it is only for security purposes.

You will receive a confirmation email as soon as your account is approved.

Hoping you enjoy your stay, please accept our kind regards.

-- {site_name} Team.
EOT;

/**
 * Indonesia version.
 */
$messages['indonesia'] = <<<EOT
Halo {name},

Terima kasih telah bergabung dengan kami di {site_anchor}. Akun Anda dibuat tetapi membutuhkan persetujuan dari admin situs sebelum aktif.
Kami dengan tulus meminta maaf atas langkah penting ini, tetapi ini hanya untuk tujuan keamanan.

Anda akan menerima email konfirmasi segera setelah akun Anda disetujui.

Berharap Anda menikmati masa tunggu Anda, terimalah salam kami.

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
echo  apply_filters('email_users_manual_activation', $message, $lang);
