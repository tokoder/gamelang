<div class="bg-light p-5 mb-3 rounded">
    <h1>Welcome to CodeIgniter <?=config_item('site_name')?>!</h1>

    <p class="lead">The page you are looking at is being generated dynamically by CodeIgniter <?=config_item('site_name')?>.</p>

    <p>
        If you would like to edit this page you'll find it located at:
        <code>application/views/welcome_message.php</code>
        <br>
        The corresponding controller for this page is found at:
        <code>application/controllers/Welcome.php</code>
    </p>

    <p>If you are exploring CodeIgniter <?=config_item('site_name')?> for the very first time, you should start by reading the <a href="https://github.com/tokoder/gamelang/wiki">User Guide</a>.</p>

    <p class="footer"><?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

<?php do_action('widget_homepage'); ?>