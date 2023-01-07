<div class="row g-3">
    <div class="col-xs-12 col-sm-6 col-md-9">
        <div class="bg-light p-3 p-md-5 mb-3 rounded">
            <h1>Welcome to CodeIgniter <?=config_item('site_name')?>!</h1>

            <p class="lead">
                The page you are looking at is being generated dynamically by CodeIgniter <?=config_item('site_name')?>.
            </p>

            <p>
                The corresponding controller for this page is found at:
                <code>application/controllers/Welcome.php</code>
            </p>

            <p class="footer">
                If you are exploring CodeIgniter <?=config_item('site_name')?> for the very first time, you should start by reading the <a href="https://github.com/tokoder/gamelang/wiki">User Guide</a>.
                <br>
                <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
            </p>
        </div>

        <?php do_action('widget_homepage'); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <?= get_partial('sidebar'); ?>
    </div>
</div>
