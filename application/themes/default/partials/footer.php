<footer class="footer navbar navbar-text mt-auto bg-light">
    <div class="container">
        <span>
            <?= sprintf('Copyright &copy; %s %s', date('Y'), anchor(prep_url('github.com/tokoder/gamelang'), get_option('site_name'))); ?>.
        </span>
        <span>
            <?php if (ENVIRONMENT === 'development'): ?>
            <?= sprintf('<abbr title="Render Time">RT</abbr>: <strong>%s</strong>', '{elapsed_time}')?>.
            <?= sprintf('<abbr title="Theme Render Time">TT</abbr>: <strong>%s</strong>', '{theme_time}')?>.
            <?php endif; ?>

            <?= sprintf('<abbr title="Version">Version</abbr>: <strong>%s</strong>', anchor(prep_url('github.com/tokoder/gamelang/releases'), CG_VERSION, 'class="text-decoration-none text-secondary" target="_blank"'))?>.
        </span>
    </div>
</footer>