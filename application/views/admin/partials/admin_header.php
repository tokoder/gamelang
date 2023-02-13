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

<header>
	<nav class="navbar navbar-expand-xxl navbar-dark bg-primary navbar-admin" aria-label="Main navigation">
		<div class="container-fluid">
			<?php
			/**
			 * Apply filter on the displayed brand on dashboard.
			 */
			$brand = html_tag('a', array(
				'href'  => '%s',
				'class' => 'navbar-brand apps-logo',
			), '<b>'.get_option('site_name').'</b> %s');
			$brand = apply_filters('admin_logo', $brand);
			echo sprintf($brand, admin_url(), '');
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

				// contents menu
				$admin_menu[] = array(
					'parent'     => NULL,
					'order'      => 3,
					'id'         => '_content_menu',
					'permission' => 'contents',
					'slug'       => admin_url('contents'),
					'name'       => __('lang_contents'),
				);

				// components menu
				$admin_menu[] = array(
					'parent'     => NULL,
					'order'      => 3,
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
				$this->menus->set_hidden_items(['_content_menu', '_admin_menu']);
				$this->menus->set_divided_items(apply_filters('_admin_menu_divided', []));
				$this->menus->set_items(apply_filters('_admin_menu', $admin_menu));
				echo $this->menus->render(array(
					'nav_tag_open'        => '<ul class="nav navbar-nav me-auto">',
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

                // navbar_right_menu
                $this->menus->set_items(apply_filters('navbar_right_menu', []));
                echo $this->menus->render([
                    'nav_tag_open'        => '<ul class="navbar-nav ms-auto">',
                    'parentl1_tag_open'   => '<li class="nav-item dropdown">',
                    'parentl1_anchor'     => '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="%s">%s</a>',
                    'parent_tag_open'     => '<li class="nav-item dropdown">',
                    'parent_anchor'       => '<a class="dropdown-item dropdown-toggle" href="%s" data-bs-toggle="dropdown">%s</a>',
                    'item_tag_open'       => '<li class="nav-item">',
                    'item_anchor'         => '<a href="%s" class="nav-link">%s</a>',
                    'item_divider'        => '<li><hr class="dropdown-divider"></li>',
                    'children_tag_open'   => '<ul class="dropdown-menu dropdown-menu-sm-end">',
                    'children_anchor'     => '<a href="%s" class="dropdown-item">%s</a>',
                ]);

				echo anchor(site_url(), fa_icon('external-link').__('lang_go_to_'.get_option('site_name')), 'class="btn btn-outline-light ms-3"');

				// User dropdown.
				echo '<div class="dropdown text-end ms-sm-3">';
				if(user_permission('admin_panel')) :
				$user_menu[] = array(
					'parent' => NULL,
					'order'  => 1,
					'id'     => 'admin',
					'icon'   => 'fa fa-shield',
					'slug'   => config_item('site_admin'),
					'name'   => __('lang_admin_panel'),
				);
				endif;
				$user_menu[] = array(
					'parent' => NULL,
					'order'  => 99,
					'id'     => 'logout',
					'slug'   => 'logout',
					'icon'   => 'fa fa-sign-out',
					'name'   => __('lang_logout'),
				);
				echo anchor('#', user_avatar(35, $c_user->id, 'class="rounded-circle border border-3"'), 'data-bs-toggle="dropdown"');
				$this->menus->set_divided_items(apply_filters('_users_menu_divided', ['profile', 'logout']));
				$this->menus->set_items(apply_filters('users_menu', $user_menu));
				echo $this->menus->render([
					'nav_tag_open'=>'<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">',
					'item_tag_open' => '<li>',
					'item_divider' => '<li class="dropdown-divider"></li>',
					'item_anchor' => '<a href="%s" class="dropdown-item">%s</a>'
				]);
				// Closing tag (right menu).
				echo '</div>';
				?>
			</div>
		</div>
	</nav>

	<nav class="navbar border-bottom mb-3" role="banner">
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
			if ( has_action('admin_subhead'))
			{
				/**
				 * Fires inside the admin subhead section.
				 */
				do_action('admin_subhead');

				echo '<div class="btn-toolbar ms-auto">';

				/**
				 * Display help for the current section.
				 */
				if (isset($package['has_help'])
					&& true === $package['has_help']
					OR isset($page_help)) {

					$href = (true === $package['contexts']['help'] ? admin_url('help/'.$package['folder']) : $package['contexts']['help']);
					$url = isset($page_help) ? $page_help : $href;
					echo html_tag('a', array(
						'href'  => $url,
						'target' => '_blank',
						'class'  => 'btn btn-info btn-sm btn-icon',
					), fa_icon('question-circle').__('lang_help'));
				}

				/**
				 * Display settings for the current section.
				 */
				if (isset($page_setting)) {
					echo $page_setting;
				}
				elseif (isset($package['has_setting'])
					&& true === $package['has_setting']
					&& strpos($this->router->fetch_directory(), 'setting') == false
				) {
					echo html_tag('a', array(
						'href'  => admin_url('setting/'.$package['folder']),
						'class' => 'btn btn-secondary btn-sm btn-icon ms-2',
					), fa_icon('cog').__('lang_settings'));
				}

				if (isset($page_donate)) {
					/**
					 * Display donate for the current section.
					 */
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
	</nav>
</header>