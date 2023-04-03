<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler me-2 p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php
            $brand = anchor('', get_option('site_name'), 'class="navbar-brand"');
            $brand = apply_filters('site_logo', $brand);
            echo $brand;
            ?>

            <div class="d-flex d-md-none align-items-center ms-auto">
                <?= get_partial('userbar', null, false) ?>
            </div>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <?php
                $this->menus->set_items(apply_filters('menu_location', [], 'main'));
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
                ?>

                <div class="d-none d-md-flex align-items-center ms-auto">
                    <?= get_partial('userbar', null, false) ?>
                </div>
            </div>
        </div>
    </nav>

    <?php do_action('appbar'); ?>

    <?php if ( has_action('subhead')) : ?>
    <nav class="subnavbar mb-3 navbar navbar-expand-md sticky-top z-50" role="banner">
        <div class="container-md d-flex align-items-center">
            <?php
            /**
            * Fires inside the subhead section.
            */
            do_action('subhead');
            ?>
        </div>
    </nav>
    <?php endif; ?>
</header>