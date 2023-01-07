<footer class="footer navbar mt-auto bg-light">
    <div class="container">
        <span class="navbar-text">
            <?php echo anchor('', get_option('site_name')) ?>
            &copy; Copyright <?php echo date('Y') ?>.
        </span>
        <span class="navbar-text">
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