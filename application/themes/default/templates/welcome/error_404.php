<h1><?php _e('Opps! Page not found.')?></h1>

<p class="lead"><?php _e('Sorry, the page you\'re looking for doesn\'t exist. If you think something is broken, report a problem.')?></p>

<?= apply_filters('anchor_missing', anchor('/', 'return home')); ?>