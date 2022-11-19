<nav class="navbar navbar-expand-md navbar-dark bg-primary">
    <div class="container-fluid">
        <?php echo anchor('', get_option('site_name'), 'class="navbar-brand"') ?>
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

            <ul class="navbar-nav ms-auto mb-2 mb-md-0">
                <?php if (isset($site_languages) && count($site_languages) >= 1): ?>
                <li class="nav-item menu-menu dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $current_language['name']; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php foreach($site_languages as $folder => $lang): ?>
                        <li>
                        <?php echo anchor('load/language/'.$folder,
                            $lang['name_en'].'<small class="text-muted ms-auto">'.$lang['name'].'</small>',
                            'class="dropdown-item d-flex align-items-center"'
                        ) ?>
                        </li>
                        <?php endforeach; unset($folder, $lang); ?>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($this->auth->online()): ?>
                <li class="nav-item user-menu dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $c_user->full_name; ?>
                        <?php echo user_avatar(24, $c_user->id, 'class="img-circle"'); ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><?php echo anchor($c_user->username, __('lang_view_profile'), 'class="dropdown-item"') ?></li>
                        <li><?php echo anchor('settings', __('lang_settings'), 'class="dropdown-item"') ?></li>
                        <li class="dropdown-divider"></li>
                        <li><?php echo anchor('logout', __('lang_LOGOUT'), 'class="dropdown-item"') ?></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <?php if ( ! $this->auth->online()): ?>
            <?php echo anchor('login', __('lang_login'), 'class="btn btn-dark"') ?>
            <?php if (get_option('allow_registration', false) === true): ?>
            &nbsp;<?php echo anchor('register', __('lang_create_account'), 'class="btn btn-light"') ?>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</nav>