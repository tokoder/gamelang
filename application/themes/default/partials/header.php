<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary mb-3">
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
                $this->menus->set_items(apply_filters('navbar_menu', []));
                echo $this->menus->render([
                    'nav_tag_open' => '<ul class="navbar-nav">',
                    'item_tag_open' => '<li class="nav-item">',
                    'item_anchor' => '<a href="%s" class="nav-link">%s</a>'
                ]);
                ?>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($site_languages) && count($site_languages) >= 1): ?>
                    <li class="nav-item menu-menu dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo fa_icon('language').$current_language['name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <?php foreach($site_languages as $folder => $lang): ?>
                            <li>
                            <?php echo anchor('gamelang/lang/'.$folder.'?next='.rawurlencode(uri_string()),
                                $lang['name_en'].'<small class="text-muted ms-auto">'.$lang['flag'].'</small>',
                                'class="dropdown-item d-flex align-items-center"'
                            ) ?>
                            </li>
                            <?php endforeach; unset($folder, $lang); ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="navbar-nav-wrap mt-3 mt-sm-0 ms-sm-2">
                    <?= do_action('navbar_right_menu'); ?>
                    <?php if ( ! $this->auth->online()): ?>
                        <?php if (get_option('allow_registration', false) === true): ?>
                        &nbsp;<?php echo anchor('register', fa_icon('external-link').__('lang_create_account'), 'class="btn btn-light"') ?>
                        <?php endif; ?>
                        <?= anchor('login', fa_icon('sign-in').__('lang_login'), 'class="btn btn-dark"') ?>
                    <?php else : ?>
                        <div class="dropdown d-inline-block ms-2">
                            <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= user_avatar(35, $c_user->id, 'class="rounded-circle border border-3"'); ?>
                            </a>
                            <?php
                            if(user_permission('admin_panel')) :
                            $user_menu[] = array(
                                'parent' => NULL,
                                'order'  => 1,
                                'id'     => 'admin',
                                'slug'   => config_item('site_admin'),
                                'name'   => __('lang_admin_panel'),
                            );
                            endif;
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
                                'name'   => __('lang_settings'),
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
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>