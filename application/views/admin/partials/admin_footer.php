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

<footer class="footer navbar navbar-text mt-auto bg-light">
    <div class="container-fluid">
        <?php
		/**
		 * Filters the "Thank you" text displayed in the dashboard footer.
		 * This line can be removed/overridden using the "admin_footer_text".
		 */
		$thankyou = sprintf(__('Thank your for creating with <em>%s</em>'), anchor(prep_url('github.com/tokoder/gamelang'), 'Gamelang', 'target="_blank"'));
		$thankyou = apply_filters('admin_footer_text', $thankyou);
		echo html_tag('span', array(
			'id'    => 'footer-thankyou',
		), $thankyou);

		/**
		 * Fires right after the opening tag of the admin footer.
		 */
        if (ENVIRONMENT === 'development'):
            $in_admin_footer[] = array(
                'parent' => NULL,
                'id'     => 'elapsed_time',
                'slug'   => site_url(),
                'name'   => sprintf('<abbr title="Render Time">RT</abbr>: <strong>%s</strong>', '{elapsed_time}'),
            );
            $in_admin_footer[] = array(
                'parent' => NULL,
                'id'     => 'theme_time',
                'slug'   => site_url(),
                'name'   => sprintf('<abbr title="Theme Render Time">TT</abbr>: <strong>%s</strong>', '{theme_time}'),
            );
        endif;
		$in_admin_footer[] = array(
			'parent' => NULL,
			'order'  => 0,
			'id'     => 'upgrade_menu',
			'slug'   => prep_url('github.com/tokoder/gamelang/releases'),
			'name'   => apply_filters('admin_version_text', sprintf('<abbr title="Version">Version</abbr>: <strong>%s</strong>', CG_VERSION)),
			'attributes' => ['target'=>"_blank"],
		);
		$in_admin_footer[] = array(
			'parent' => NULL,
			'order'  => 0,
			'id'     => 'language_menu',
			'slug'   => site_url(),
			'name'   => $current_language['name'],
		);
		foreach (force_array($site_languages) as $folder => $lang) {
			$in_admin_footer[] = array(
				'parent' => 'language_menu',
				'order'  => 0,
				'id'     => 'language_menu_'.$folder,
				'slug'   => site_url('gamelang/lang/'.$folder.'?next='.rawurlencode(uri_string())),
				'name'   => $lang['name_en'].html_tag('span', array(
					'class' => 'text-muted float-end'
				), $lang['flag']),
			);
			unset($lang);
		}
		$this->menus->set_items(apply_filters('in_admin_footer', $in_admin_footer));
		echo $this->menus->render([
			'nav_tag_open'        => '<ul class="navbar list-unstyled p-0 m-0 gap-sm-3">',
			'parentl1_tag_open'   => '<li class="nav-item dropup">',
			'parentl1_anchor'     => '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="%s">%s</a>',
			'parent_tag_open'     => '<li class="nav-item dropdown">',
			'parent_anchor'       => '<a class="dropdown-item dropdown-toggle" href="%s" data-bs-toggle="dropdown">%s</a>',
			'item_tag_open'       => '<li class="nav-item">',
			'item_anchor'         => '<a href="%s" class="nav-link">%s</a>',
			'item_divider'        => '<li><hr class="dropdown-divider"></li>',
			'children_tag_open'   => '<ul class="dropdown-menu dropdown-menu-end">',
			'children_anchor'     => '<a href="%s" class="dropdown-item">%s</a>',
		]);
		?>
    </div>
</footer>