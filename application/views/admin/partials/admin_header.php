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
			<ul class="navbar-nav me-auto">
				<?php
				// 1. Settings dropdown.
				echo '<li class="nav-item dropdown">',
					html_tag('a', array(
						'href'        => '#',
						'class'       => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_settings')),
					'<div class="dropdown-menu">',

						// Global settings.
						admin_anchor(
							'settings',
							__('lang_global_settings'),
							'class="dropdown-item"'
						),

						// System Information
						admin_anchor(
							'settings/sysinfo',
							__('lang_system_information'),
							'class="dropdown-item"'
						);

						/**
						 * Fires inside the settings menu.
						 */
						if ( has_action('_settings_menu')) {
							echo '<div class="dropdown-divider"></div>';
							do_action('_settings_menu');
						}
				// Closing tag (settings menu)
				echo '</div></li>';

				// 2. Users menu.
				echo '<li class="nav-item dropdown">',
					admin_anchor('users', __('lang_users'), array(
						'class' => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					)),
					'<div class="dropdown-menu">',
						// Manage users.
						html_tag('a', array(
							'href'  => admin_url('users'),
							'class' => 'dropdown-item',
						), __('lang_users_manage'));

						/**
						 * Fires inside users menu.
						 */
						if ( has_action('_user_menu')) {
							echo '<div class="dropdown-divider"></div>';
							do_action('_user_menu');
						}
				// Closing tag (users menu).
				echo '</div></li>';

				/**
				 * Fires right after users dropdown menu.
				 */
				do_action('_admin_navbar');

				// 3. Display menu for packages with content controller.
				if ( has_action('_content_menu')) {
					// Menu opening tag.
					echo '<li class="nav-item dropdown">',
					html_tag('a', array(
						'href' => '#',
						'class'       => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_content')),
					'<div class="dropdown-menu">';

					// Do the actual action.
					do_action('_content_menu');

					// Menu closing tag.
					echo '</div></li>';
				}

				// 4. Display menu for packages with admin controller.
				if ( has_action('_admin_menu')) {
					// Menu opening tag.
					echo '<li class="nav-item dropdown">',
					html_tag('a', array(
						'href' => '#',
						'class' => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_components')),
					'<div class="dropdown-menu">';

					// Do the actual action.
					do_action('_admin_menu');

					// Menu closing tag.
					echo '</div></li>';
				}

				// 5. Extensions menu.
				echo '<li class="nav-item dropdown">',
					html_tag('a', array(
						'href' => '#',
						'class' => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_extensions')),
					'<div class="dropdown-menu">',
					admin_anchor('packages', __('lang_packages'), 'class="dropdown-item"'),
					admin_anchor('themes', __('lang_themes'), 'class="dropdown-item"'),
					admin_anchor('languages', __('lang_languages'), 'class="dropdown-item"');
				// Closing tag (extensions menu).
				echo '</div></li>';

				// 6. Display menu for packages with reports controller.
				if ( has_action('_reports_menu')) {
					// Menu opening tag.
					echo '<li class="nav-item dropdown">',
					html_tag('a', array(
						'href' => '#',
						'class'       => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_reports')),
					'<div class="dropdown-menu">';

					echo admin_anchor('reports', __('manage_reports'), 'class="dropdown-item"'),
					'<div class="dropdown-divider"></div>';

					// Do the actual action.
					do_action('_reports_menu');

					'</div></li>';
				} else {
					echo '<li class="nav-item dropdown">',
					admin_anchor('reports', __('lang_reports'), 'class="nav-link"'),
					'</li>';
				}

				// 7. Help menu.
				echo '<li class="nav-item dropdown">';
					echo html_tag('a', array(
						'href'        => '#',
						'class'       => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), __('lang_help')),
					'<div class="dropdown-menu">';
						// documentation.
						$wiki_url = apply_filters('wiki_url', 'https://github.com/tokoder/gamelang/wiki');
						if ( ! empty($wiki_url)) {
							echo html_tag('a', array(
								'href'   => $wiki_url,
								'class'  => 'dropdown-item',
								'target' => '_blank',
							), __('lang_documentation'));
						}

						// Display packages with "Help.php" controllers.
						if ( has_action('_help_menu')) {
							echo '<div class="dropdown-divider"></div>';
							do_action('_help_menu');
						}

						echo '<div class="dropdown-divider"></div>',

						// Link to extensions page.
						html_tag('a', array(
							'href' => 'javascript:void(0)',
							'class' => 'dropdown-item disabled',
						), __('lang_extensions')),

						// Link to translations page.
						html_tag('a', array(
							'href' => 'javascript:void(0)',
							'class' => 'dropdown-item disabled',
						), __('lang_translations')),

						// Link to shop page.
						html_tag('a', array(
							'href' => 'javascript:void(0)',
							'class' => 'dropdown-item disabled',
						), __('lang_shop'));

				// Menu closing tag.
				echo '</div></li>';
				?>
			</ul>
			<ul class="navbar-nav">
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
					), $current_language['name']),
					'<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end text-small shadow">';

					// Language list.
					foreach ($site_languages as $folder => $lang) {
						echo html_tag('a', array(
							'href' => site_url('resource/language/'.$folder.'?next='.current_url()),
							'class' => 'dropdown-item',
						), $lang['name_en'].html_tag('span', array(
							'class' => 'text-muted float-end'
						), $lang['name']));
					}
					unset($lang);

					echo '</div></li>';
				} else {
					echo '<li id="lang-dropdown"></li>';
				}

				// 2. View site anchor.
				echo html_tag('li', array(
					'class' => 'nav-item view-site'
				), html_tag('a', array(
					'href'   => site_url(),
					'target' => '_blank',
					'class'  => 'nav-link',
				), __('lang_view_site').fa_icon('external-link ms-1')));

				// 3. User dropdown.
				echo '<li class="nav-item dropdown user-menu">',

					html_tag('a', array(
						'href' => '#',
						'class' => 'nav-link dropdown-toggle',
						'data-bs-toggle' => 'dropdown',
					), fa_icon('user'));

					$user_menu[] = array(
						'parent' => NULL,
						'id'     => 'settings',
						'slug'  => admin_url('users/edit/'.($c_user ? $c_user->id : 0)),
						'name' => __('lang_settings'),
					);
					$user_menu[] = array(
						'parent' => NULL,
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
				echo '</li>';
				?>
			</ul>
        </div>
    </div>
</nav>

<header class="header bg-light" id="header" role="banner">
    <div class="container py-2 d-flex justify-content-between align-items-center">
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

			if (isset($package['has_help']) && true === $package['has_help']) {
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

			if (isset($package['has_settings']) && true === $package['has_settings'] && $this->router->fetch_class() !== 'settings') {
				echo html_tag('a', array(
					'href'  => admin_url('settings/'.$package['folder']),
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