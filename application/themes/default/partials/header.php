<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container-fluid">
            <?php
            $brand = anchor('', get_option('site_name'), 'class="navbar-brand"');
            $brand = apply_filters('site_logo', $brand);
            if ( ! empty($brand)) {
                echo $brand;
            }
            ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <?php
                // navbar_menu
                $this->menus->set_items(apply_filters('navbar_menu', []));
                echo $this->menus->render([
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
                ]);

                // navbar_right_menu
                $content_menu[] = array(
                    'parent' => NULL,
                    'order'  => 99,
                    'id'     => '_content_menu',
                    'slug'   => admin_url(),
                );
				$this->menus->set_hidden_items(['_content_menu']);
                $this->menus->set_items(apply_filters('navbar_right_menu', $content_menu));
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
                ?>

                <div class="text-end ms-sm-3">
                <?php
                if ( ! $this->auth->online()):
                    if (get_option('allow_registration', false) === true):
                    echo anchor('register', fa_icon('external-link').__('lang_create_account'), 'class="btn btn-light"');
                    endif;
                    echo anchor('login', fa_icon('sign-in').__('lang_login'), 'class="btn btn-dark ms-2"');
                else :
                    echo '<div class="dropdown">';
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
                        'nav_tag_open'=>'<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-sm-end">',
                        'item_tag_open' => '<li>',
                        'item_divider' => '<li class="dropdown-divider"></li>',
                        'item_anchor' => '<a href="%s" class="dropdown-item">%s</a>'
                    ]);
                    echo '</div>';
                endif;
                ?>
                </div>
            </div>
        </div>
    </nav>

    <?php
    if ( ! empty(apply_filters('components_menu', []))) :
    ?>
    <nav class="nav-scroller bg-body shadow-sm sticky-top">
        <div class="container">
            <div class="row">
            <?php
            // compoenent menu
            $this->menus->set_items(apply_filters('components_menu', []));
            echo $this->menus->render([
                'nav_tag_open'        => '<ul class="nav" aria-label="Secondary navigation">',
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
            ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <?php if ( has_action('subhead')) : ?>
    <nav class="navbar border-bottom mb-3" role="banner">
        <div class="container">
            <?php
            /**
             * Fires on page header.
             */
            // Default Title.
            $default_title = __('lang_beranda');

            /**
             * Page title using Data_Cache object.
             * @var 	string
             */
            // Filtered title.
            isset($page_title) OR $page_title = $default_title;
            $page_title = apply_filters('page_title', $page_title);

            echo html_tag('h4', array('class' => 'page-title me-3 my-0'), $page_title);

            echo '<div class="btn-toolbar ms-auto">';

            /**
             * Fires inside the subhead section.
             */
            do_action('subhead');

            echo '</div>';
            ?>
        </div>
    </nav>
    <?php endif; ?>
</header>