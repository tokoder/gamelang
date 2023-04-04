<footer class="footer mt-auto">

    <?php do_action('widget_footer'); ?>

    <?php if ($footer_menu = apply_filters('menu_location', false, 'footer')) : ?>
    <div class="footer-top bg-secondary bg-opacity-10">
        <div class="container">
            <div class="list-group">
                <?php
                $items = $this->menus->prepare_items($footer_menu);
                foreach ($items as $item) : ?>
                    <ul class="footer-list list-unstyled">
                        <li class="list-title cursor-pointer"><?=ucwords($item['name']);?></li>
                        <?php foreach ($item['children'] as $key => $value) : ?>
                        <li class="list-link"><a href="<?=$value['slug'];?>"><?=ucwords($value['name']);?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <nav class="footer-copyright bg-light navbar p-0">
        <div class="container-fluid">
            <?php
            $copy = sprintf('%s &copy; %s - All Rights Reserved.', anchor('', get_option('site_name')), date('Y'));
            $copy = apply_filters('site_footer_text', $copy);
            echo html_tag('span', 'class="navbar-text"', $copy);

            /**
             * Fires right after the opening tag of the admin footer.
             */
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
            $in_footer[] = array(
                'parent' => NULL,
                'order'  => 0,
                'id'     => 'language_menu',
                'slug'   => site_url(),
                'name'   => $current_language['name'],
            );
            $in_footer[] = array(
                'parent' => NULL,
                'id'     => 'upgrade_menu',
                'slug'   => prep_url('github.com/tokoder/gamelang/releases'),
                'name'   => sprintf('<abbr title="Version">Version</abbr>: <strong>%s</strong>', CG_VERSION),
                'attributes' => ['target'=>"_blank"],
            );
            if (ENVIRONMENT === 'development'):
                $in_footer[] = array(
                    'parent' => NULL,
                    'id'     => 'elapsed_time',
                    'slug'   => '#',
                    'name'   => sprintf('<abbr title="Render Time">RT</abbr>: <strong>%s</strong>', '{elapsed_time}'),
                );
                $in_footer[] = array(
                    'parent' => NULL,
                    'id'     => 'theme_time',
                    'slug'   => '#',
                    'name'   => sprintf('<abbr title="Theme Render Time">TT</abbr>: <strong>%s</strong>', '{theme_time}'),
                );
            endif;
            $this->menus->set_items(apply_filters('in_footer', $in_footer));
            echo $this->menus->render([
                'nav_tag_open'        => '<ul class="navbar-nav ms-sm-auto flex-row flex-wrap flex-sm-row-reverse gap-3">',
                'parentl1_tag_open'   => '<li class="nav-item dropup">',
                'parentl1_anchor'     => '<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="%s">%s</a>',
                'parent_tag_open'     => '<li class="nav-item dropup">',
                'parent_anchor'       => '<a class="nav-link dropdown-toggle" href="%s" data-bs-toggle="dropdown">%s</a>',
                'item_tag_open'       => '<li class="nav-item">',
                'item_anchor'         => '<a href="%s" class="nav-link">%s</a>',
                'item_divider'        => '<li><hr class="dropdown-divider"></li>',
                'children_tag_open'   => '<ul class="dropdown-menu position-absolute dropdown-menu-start dropdown-menu-sm-end">',
                'children_anchor'     => '<a href="%s" class="dropdown-item">%s</a>',
            ]);
            ?>
        </div>
    </nav>
</footer>