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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary navbar-admin" aria-label="Main navigation">
    <div class="container-fluid">
        <?php
		/**
		 * Apply filter on the displayed brand on dashboard.
		 */
		$brand = html_tag('a', array(
			'href'  => admin_url(),
			'class' => 'navbar-brand apps-logo',
		), get_option('site_name'));
		$brand = apply_filters('admin_logo', $brand);
		if ( ! empty($brand)) {
			echo $brand;
		}
		?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-admin" aria-controls="navbar-admin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-admin">
			<?php
			// Settings menu
			$admin_menu[] = array(
				'parent'     => NULL,
				'order'      => 0,
				'id'         => '_setting_menu',
				'permission' => 'settings',
				'slug'       => admin_url('settings'),
				'name'       => __('lang_settings'),
			);

			// users menu
			$admin_menu[] = array(
				'parent'     => NULL,
				'order'      => 2,
				'id'         => '_user_menu',
				'permission' => 'users',
				'slug'       => admin_url('users'),
				'name'       => __('lang_users'),
			);

			// content menu
			$admin_menu[] = array(
				'parent' => NULL,
				'order'  => 3,
				'id'     => '_content_menu',
				'slug'   => admin_url('contents'),
				'name'   => __('lang_content'),
			);

			// admin menu
			$admin_menu[] = array(
				'parent'     => NULL,
				'order'      => 4,
				'id'         => '_admin_menu',
				'permission' => 'components',
				'slug'       => admin_url('components'),
				'name'       => __('lang_components'),
			);

			// Extensions menu.
			$admin_menu[] = array(
				'parent'     => NULL,
				'order'      => 5,
				'id'         => '_extensions_menu',
				'permission' => 'extension',
				'slug'       => admin_url('extensions'),
				'name'       => __('lang_extensions'),
			);

			// Reports menu.
			$admin_menu[] = array(
				'parent'     => NULL,
				'order'      => 6,
				'id'         => '_report_menu',
				'permission' => 'reports',
				'slug'       => admin_url('reports'),
				'name'       => __('lang_reports'),
			);

			// Helps menu.
			$admin_menu[] = array(
				'parent' => NULL,
				'order'  => 7,
				'id'     => '_help_menu',
				'slug'   => admin_url('help'),
				'name'   => __('lang_help'),
			);
            $this->menus->set_divided_items(apply_filters('_admin_menu_divided', []));
			$this->menus->set_items(apply_filters('_admin_menu', $admin_menu));
			echo $this->menus->render(array(
				'nav_tag_open'        => '<ul class="nav navbar-nav">',
				'parentl1_tag_open'   => '<li class="nav-item dropdown">',
				'parentl1_anchor'     => '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="%s">%s</a>',
				'parent_tag_open'     => '<li class="nav-item dropdown">',
				'parent_anchor'       => '<a class="dropdown-item dropdown-toggle" href="%s" data-bs-toggle="dropdown">%s</a>',
				'item_tag_open'       => '<li class="nav-item">',
				'item_anchor'         => '<a href="%s" class="nav-link">%s</a>',
				'item_divider'        => '<li><hr class="dropdown-divider"></li>',
				'children_tag_open'   => '<ul class="dropdown-menu">',
				'children_anchor'     => '<a href="%s" class="dropdown-item">%s</a>',
			));
			?>
			<ul class="navbar-nav ms-auto">
				<?php
				/**
				 * Fires right after users dropdown menu.
				 */
				do_action('_admin_navbar_right');

				// 1. Languages dropdown.
				if ($site_languages) {
					echo '<li class="nav-item dropdown" id="lang-dropdown">',

					html_tag('a', array(	// Dropdown toggler.
						'href' => '#',
						'class' => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), fa_icon('language').$current_language['name']),
					'<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end text-small shadow">';

					// Language list.
					foreach ($site_languages as $folder => $lang) {
						echo html_tag('a', array(
							'href' => site_url('gamelang/lang/'.$folder.'?next='.current_url()),
							'class' => 'dropdown-item',
						), $lang['name_en'].html_tag('span', array(
							'class' => 'text-muted float-end'
						), $lang['flag']));
					}
					unset($lang);

					echo '</div></li>';
				}
				?>
			</ul>
			<div class="navbar-nav-wrap mt-3 mt-sm-0 ms-sm-2">
				<?php
				// View site anchor.
				echo html_tag('a', array(
					'href'   => site_url(),
					'target' => '_blank',
					'class'  => 'btn btn-light view-site me-2',
				), fa_icon('external-link').__('lang_view_site'));

				// User dropdown.
				echo '<div class="dropdown user-menu d-inline-block">',

					anchor('#', user_avatar(35, $c_user->id, 'class="rounded-circle border border-3"'), 'data-bs-toggle="dropdown"');

					$user_menu[] = array(
						'parent' => NULL,
						'order'  => 2,
						'id'     => 'profile',
						'slug'   => 'profile/'.$c_user->username,
						'name'   => __('lang_profile'),
					);
					$user_menu[] = array(
						'parent' => NULL,
						'order'  => 3,
						'id'     => 'settings',
						'slug'   => 'settings',
						'name'   => __('update_profile'),
					);
					$user_menu[] = array(
						'parent' => NULL,
						'order'  => 4,
						'id'     => 'change_password',
						'slug'   => 'settings/change-password',
						'name'   => __('change_password')
					);
					$user_menu[] = array(
						'parent' => NULL,
						'order'  => 99,
						'id'     => 'logout',
						'slug'   => 'logout',
						'name'   => __('lang_logout'),
					);
					$this->menus->set_items(apply_filters('users_menu', $user_menu));
					echo $this->menus->render([
						'nav_tag_open'=>'<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">',
						'item_tag_open' => '<li>',
						'item_anchor' => '<a href="%s" class="dropdown-item">%s</a>'
					], ['logout'], '<li class="dropdown-divider"></li>');

				// Closing tag (right menu).
				echo '</div>';
				?>
			</div>
        </div>
    </div>
</nav>

<header class="header navbar bg-light border-bottom" id="header" role="banner">
    <div class="container">
        <?php
		/**
		 * Fires on page header.
		 */
		// Default Icon and Title.
		$default_icon  = 'home';
		$default_title = __('lang_dashboard');

		/**
		 * Page icon using Data_Cache object.
		 * @var 	string
		 */
		isset($page_icon) OR $page_icon = $default_icon;
		$page_icon .= ' page-icon';

		/**
		 * Page title using Data_Cache object.
		 * @var 	string
		 */
		isset($page_title) OR $page_title = $default_title;

		// Filtered icon and title.
		$page_icon  = apply_filters('admin_page_icon', $page_icon);
		$page_title = apply_filters('admin_page_title', $page_title);

		echo html_tag('h4', array('class' => 'page-title me-3 my-0'), fa_icon($page_icon).$page_title);
		/**
		 * Subhead section.
		 */
		if ( has_action('admin_subhead')
			OR (isset($package['has_help']) && true === $package['has_help'])
			OR isset($page_help)
		) {
			/**
			 * Fires inside the admin subhead section.
			 */
			do_action('admin_subhead');

			/**
			 * Display help/settings for the current section.
			 */
			echo '<div class="my-2 my-lg-0 ms-auto">';

			if (isset($package['has_help'])
				&& true === $package['has_help']) {
				echo html_tag('a', array(
					'href'   => (true === $package['contexts']['help'] ? admin_url('help/'.$package['folder']) : $package['contexts']['help']),
					'target' => '_blank',
					'class'  => 'btn btn-info btn-sm btn-icon',
				), fa_icon('question-circle').__('lang_help'));
			} elseif (isset($page_help)) {
				echo html_tag('a', array(
					'href'   => $page_help,
					'target' => '_blank',
					'class'  => 'btn btn-info btn-sm btn-icon',
				), fa_icon('question-circle').__('lang_help'));
			}

			if (isset($package['has_setting'])
				&& true === $package['has_setting']
				&& strpos($this->router->fetch_directory(), 'setting') == false) {
				echo html_tag('a', array(
					'href'  => admin_url('setting/'.$package['folder']),
					'class' => 'btn btn-secondary btn-sm btn-icon ms-2',
				), fa_icon('cog').__('lang_settings'));
			}

			if (isset($page_donate)) {
				echo html_tag('a', array(
					'href'   => $page_donate,
					'target' => '_blank',
					'class'  => 'btn btn-warning btn-sm btn-icon ms-2',
				), fa_icon('money').__('lang_DONATE'));
			}

			echo '</div>';
		}
		?>
    </div>
</header>