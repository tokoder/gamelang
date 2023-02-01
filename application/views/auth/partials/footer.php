<footer class="footer navbar navbar-text bg-light">
    <div class="container">
        <span>
            <?php
            echo '<span class="me-2">',
            html_tag('a', array(
                'href' => site_url(),
            ), fa_icon('external-link').__('lang_go_homepage')),
            '</span>';

            $default_copyright = sprintf(__('&copy; %s %s'), date('Y'), get_option('site_name'));
            $footer_copyright = apply_filters('login_copyright', $default_copyright);
            if ( ! empty($footer_copyright)) {
                echo $footer_copyright;
            }
            ?>
        </span>
        <span>
            <?php if (ENVIRONMENT === 'development'): ?>
            <abbr title="Render Time">RT</abbr>:
            <strong>{elapsed_time}</strong>.
            <abbr title="Theme Render Time">TT</abbr>:
            <strong>{theme_time}</strong>.
            <?php endif; ?>
            <abbr title="Version">Version</abbr>:
            <strong><a href="<?=site_url('about')?>" class="text-decoration-none text-secondary"><?=CG_VERSION?></a></strong>.
        </span>
    </div>
</footer>