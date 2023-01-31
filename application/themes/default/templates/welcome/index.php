<div class="row g-3 mb-5">
    <div class="col-12">
        <div class="p-3 p-md-5 bg-light border border-dark rounded">
            <h1 class="display-4 fw-normal"><?=sprintf(__('Welcome to %s'), config_item('site_name'))?>!</h1>

            <p class="lead fw-normal">
                <?=sprintf(
                    __('The page you are looking at is being generated dynamically by %s'),
                    anchor(prep_url('github.com/tokoder/gamelang#readme'), 'CodeIgniter Gamelang', 'target="_blank"')
                )?>.
            </p>

            <p>
                <?=sprintf(
                    __('If you are exploring CodeIgniter Gamelang for the very first time, you should start by reading the %s'),
                    anchor(prep_url('github.com/tokoder/gamelang/wiki'), 'User Guide', 'target="_blank"')
                )?>.
            </p>

            <p>
                <?=sprintf(
                    __('The corresponding controller for this page is found at: %s'),
                    '<code>application/controllers/Welcome.php</code>'
                )?>.
            </p>

            <?php echo  (ENVIRONMENT === 'development') ?  '<p>CodeIgniter Version <strong>' . CI_VERSION . '</strong></p>' : '' ?>
        </div>
    </div>
</div>

<div class="row g-5 mb-5">
    <div class="col-xs-12 col-sm-6 col-md-3">
        <h3 class="fst-italic"><?php _e('Social')?></h3>
        <ol class="list-unstyled">
            <li><?=anchor(prep_url('github.com/tokoder/gamelang'), 'GitHub', 'target="_blank"')?></li>
            <li><?=anchor(prep_url('facebook.com/tokoder'), 'Facebook', 'target="_blank"')?></li>
            <li><?=anchor(prep_url('instagram.com/tokodercom'), 'Instragram', 'target="_blank"')?></li>
            <li><?=anchor(prep_url('tokoder.com'), 'Website', 'target="_blank"')?></li>
        </ol>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <h3 class="fst-italic"><?php _e('Archives')?></h3>
        <ol class="list-unstyled mb-0">
            <li><a href="#"><?= date('F Y')?></a></li>
            <li><a href="#"><?= date('F Y', strtotime('12/27/17'))?></a></li>
        </ol>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <h3 class="fst-italic"><?php _e('About')?></h3>
        <p class="mb-0">
            <?=__(get_option('site_description'))?>
            <?=sprintf(
                __('Check out these open source projects that you can quickly duplicate to a new %s repository'),
                anchor(prep_url('github.com/tokoder/gamelang'), 'GitHub', 'target="_blank"')
            )?>.
        </p>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-3">
        <h3 class="fst-italic"><?php _e('Guides')?></h3>
        <p class="mb-0">
            <?=sprintf(
                __('Read more detailed instructions and documentation on using or contributing to %s'),
                anchor(prep_url('github.com/tokoder/gamelang/wiki'), 'Gamelang', 'target="_blank"')
            )?>.
        </p>
    </div>
</div>

<?php do_action('homepage_widget'); ?>
