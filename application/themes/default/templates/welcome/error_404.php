<div class="container mt-3">
    <h1 class="display-6"><?php _e('Opps! Page not found.')?></h1>

    <p class="lead mb-3"><?php _e('Sorry, the page you\'re looking for doesn\'t exist. If you think something is broken, report a problem.')?></p>

    <?= apply_filters('anchor_missing', anchor($this->input->referrer(), 'back', 'class="btn btn-sm btn-outline-dark"')); ?>
</div>