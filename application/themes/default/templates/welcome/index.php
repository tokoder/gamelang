<div class="row g-5 py-5">
    <div class="col-md-8">
        <section>
            <div class="row">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold lh-1 mb-3"><?=__('Let\'s build from here')?></h1>
                    <p class="lead text-muted"><?=config_item('site_description')?></p>
                    <p>
                        <a href="<?=admin_url('packages')?>" class="btn btn-primary my-2"><?=__('Add new package')?></a>
                        <a href="<?=admin_url()?>" class="btn btn-secondary my-2"><?=__('Go to dashboard')?></a>
                    </p>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem;">
            <div class="p-4 mb-3 bg-light rounded">
                <h4 class="fst-italic"><?php _e('Guides')?></h4>
                <p class="mb-0">
                <?=sprintf(
                    __('Read more detailed instructions and documentation on using or contributing to %s'),
                    anchor(prep_url('github.com/tokoder/gamelang/wiki'), 'Gamelang', 'target="_blank"')
                )?>
                </p>
            </div>

            <div class="p-4">
                <h4 class="fst-italic"><?php _e('Archives')?></h4>
                <ol class="list-unstyled mb-0">
                    <li><a href="#"><?= date('F Y')?></a></li>
                    <li><a href="#"><?= date('F Y', strtotime('12/27/17'))?></a></li>
                </ol>
            </div>

            <div class="p-4">
                <h4 class="fst-italic"><?php _e('Social')?></h4>
                <ol class="list-unstyled">
                    <li><?=anchor(prep_url('github.com/tokoder/gamelang'), 'GitHub', 'target="_blank"')?></li>
                    <li><?=anchor(prep_url('facebook.com/tokoder'), 'Facebook', 'target="_blank"')?></li>
                    <li><?=anchor(prep_url('instagram.com/tokodercom'), 'Instragram', 'target="_blank"')?></li>
                    <li><?=anchor(prep_url('tokoder.com'), 'Website', 'target="_blank"')?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<?php do_action('homepage_widget'); ?>