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
?>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container-fluid">
        <?php
		/**
		 * Fires right after the opening tag of the admin footer.
		 */
		do_action('in_admin_footer');

		/**
		 * Filters the "Thank you" text displayed in the dashboard footer.
		 * This line can be removed/overridden using the "admin_footer_text".
		 */
		$thankyou = sprintf(__('lang_footer_text'), 'https://github.com/tokoder/gamelang');
		$thankyou =  apply_filters('admin_footer_text', $thankyou);
		if ( ! empty($thankyou))
		{
			echo html_tag('span', array(
				'class' => 'text-muted',
				'id'    => 'footer-thankyou',
			), $thankyou);
		}

		/**
		 * Footer version text.
		 * Can be removed or overridden using the "admin_version_text" fitler.
		 */
		$version = sprintf(__('lang_version_text'), CG_VERSION);
		$version =  apply_filters('admin_version_text', $version);
		if ( ! empty($version))
		{
			echo html_tag('span', array(
				'class' => 'text-muted float-end',
				'id'    => 'footer-upgrade',
			), $version);
		}
		?>
    </div>
</footer>