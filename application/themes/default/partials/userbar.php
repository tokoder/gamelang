<?php
/**
 * Fires right after users dropdown menu.
 */
do_action('navbar_right_menu');

if ( ! $this->auth->online()):
    echo anchor('login?next='.uri_string(), fa_icon('user').'<span class="d-none d-sm-inline-block">&nbsp;'.__('lang_login').'</span>', 'data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.__("lang_login").'" class="btn btn-light ms-2"');
else :
    if(checkUserPermission('admin_panel')) :
    $user_menu[] = array(
        'parent' => NULL,
        'order'  => 1,
        'id'     => 'admin',
        'slug'   => config_item('site_admin'),
        'name'   => __('lang_dashboard'),
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
    echo '<div class="dropdown ms-2 rounded bg-light">';
    echo anchor('#', user_avatar(40, $c_user->id, 'class="lazyload rounded border border-3"'), 'data-bs-toggle="dropdown"');
    $this->menus->set_divided_items(apply_filters('_users_menu_divided', ['profile', 'logout']));
    $this->menus->set_items(apply_filters('users_menu', $user_menu));
    echo $this->menus->render([
        'nav_tag_open'=>'<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">',
        'item_tag_open' => '<li>',
        'item_divider' => '<li class="dropdown-divider"></li>',
        'item_anchor' => '<a href="%s" class="dropdown-item">%s</a>'
    ]);
    echo '</div>';
endif;
?>