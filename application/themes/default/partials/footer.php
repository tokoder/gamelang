<footer class="footer navbar navbar-text mt-auto bg-light">
    <div class="container-fluid">
        <span>
            <?= sprintf('%s &copy; %s - All Rights Reserved.', anchor(site_url(), get_option('site_name')), date('Y')); ?>
        </span>
        <?php
        /**
         * Fires right after the opening tag of the admin footer.
         */
        if (ENVIRONMENT === 'development'):
            $in_footer[] = array(
                'parent' => NULL,
                'id'     => 'elapsed_time',
                'slug'   => site_url(),
                'name'   => sprintf('<abbr title="Render Time">RT</abbr>: <strong>%s</strong>', '{elapsed_time}'),
            );
            $in_footer[] = array(
                'parent' => NULL,
                'id'     => 'theme_time',
                'slug'   => site_url(),
                'name'   => sprintf('<abbr title="Theme Render Time">TT</abbr>: <strong>%s</strong>', '{theme_time}'),
            );
        endif;
        $in_footer[] = array(
            'parent' => NULL,
            'id'     => 'upgrade_menu',
            'slug'   => prep_url('github.com/tokoder/gamelang/releases'),
            'name'   => sprintf('<abbr title="Version">Version</abbr>: <strong>%s</strong>', CG_VERSION),
            'attributes' => ['target'=>"_blank"],
        );
        $in_footer[] = array(
            'parent' => NULL,
            'order'  => 0,
            'id'     => 'language_menu',
            'slug'   => site_url(),
            'name'   => $current_language['name'],
        );
        foreach (force_array($site_languages) as $folder => $lang) {
            $in_footer[] = array(
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
        $this->menus->set_items(apply_filters('in_footer', $in_footer));
        echo $this->menus->render([
            'nav_tag_open'        => '<ul class="navbar list-unstyled p-0 m-0 gap-1 gap-sm-3">',
            'parentl1_tag_open'   => '<li class="nav-item dropup">',
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
</footer>