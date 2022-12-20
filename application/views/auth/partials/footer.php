<footer class="footer">
    <nav class="navbar bg-light navbar-expand-sm">
        <div class="container">
            <span class="navbar-text">
                <?php
                echo '<span class="me-2">',
                html_tag('a', array(
                    'href' => site_url(),
                ), fa_icon('external-link').__('lang_go_homepage')),
                '</span>';

                $default_copyright = sprintf(__('lang_copyright'), date('Y'));
                $footer_copyright = apply_filters('login_copyright', $default_copyright);
                if ( ! empty($footer_copyright)) {
                    echo $footer_copyright;
                }
                ?>
            </span>
            <span class="navbar-text">
                <?php if (ENVIRONMENT === 'development'): ?>
                <abbr title="Render Time">RT</abbr>:
                <strong>{elapsed_time}</strong>.
                <abbr title="Theme Render Time">TT</abbr>:
                <strong>{theme_time}</strong>.
                <?php endif; ?>
                <abbr title="Version">Version</abbr>:
                <strong><a href="<?=site_url('about')?>" class="text-decoration-none text-secondary"><?=config_item('app_version')?></a></strong>.
            </span>
        </div>
    </nav>
</footer>