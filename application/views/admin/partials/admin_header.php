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
?>

<header>
	<nav class="navbar navbar-expand-md navbar-dark bg-primary navbar-admin" aria-label="Main navigation">
		<div class="container-fluid">
            <button class="navbar-toggler me-2 p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-admin" aria-controls="navbar-admin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
			<?php
			/**
			 * Apply filter on the displayed brand on dashboard.
			 */
            $brand = anchor('admin', get_option('site_name'), 'class="navbar-brand apps-logo"');
			$brand = apply_filters('admin_logo', $brand);
			echo $brand;
			?>

			<div class="d-flex d-md-none align-items-center ms-auto">
                <?= get_partial('admin_userbar', null, false) ?>
            </div>

			<div class="collapse navbar-collapse" id="navbar-admin">
				<?php
				global $back_contexts;
				$order = 0;
				foreach ($back_contexts as $key) {
					$admin_menu[] = array(
						'parent'     => NULL,
						'order'      => $order,
						'id'         => "_{$key}_menu",
						'permission' => "{$key}",
						'slug'       => admin_url("{$key}s"),
						'name'       => __("lang_{$key}s"),
					);
					$order++;
				}
				$this->menus->set_hidden_items(['_content_menu', '_component_menu']);
				$this->menus->set_divided_items(apply_filters('_admin_menu_divided', []));
				$this->menus->set_items(apply_filters('_admin_menu', $admin_menu));
				echo $this->menus->render(array(
					'nav_tag_open'        => '<ul class="navbar-nav">',
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

				// ------------------------------------------------------------------------
				// Dashboard right menu.
				// ------------------------------------------------------------------------
				echo '<div class="d-none d-md-flex align-items-center ms-auto">';
				echo get_partial('admin_userbar', null, false);
				// Closing tag (right menu).
				echo '</div>';
				?>
			</div>
		</div>
	</nav>
</header>

<nav class="navbar border-bottom mb-3" role="banner">
	<div class="container-fluid">
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

		echo html_tag('h4', array('class' => 'page-title me-3 my-0 fs-5'), fa_icon($page_icon).$page_title);
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
				&& checkUserPermission('setting')
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