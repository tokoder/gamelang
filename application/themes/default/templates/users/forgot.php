<div class="row">
	<div class="col-xs-12 col-sm-6 col-sm-push-3 col-md-4 col-md-push-4">
		<h1 class="text-center"><?php _e('lang_lost_password') ?></h1>
		<div class="panel panel-default">
			<div class="panel-body">
				<p class="text-muted"><?php _e('USERS_RECOVER_tip') ?></p><br />
				<?php echo form_open('login/recover', 'role="form" id="forgot"'), form_nonce('user-lost-password'); ?>
					<div class="form-group<?php echo form_error('identity') ? ' has-error' : '' ?>">
						<label for="identity" class="sr-only"><?php _e('lang_identity') ?></label>
						<?php echo print_input($identity, array('class' => 'form-control', 'autofocus' => 'autofocus')) ?>
						<?php echo form_error('identity', '<small class="help-block">', '</small>') ?>
					</div>
				<?php if (get_option('use_captcha', false) === true): ?>
					<div class="form-group<?php echo form_error('captcha') ? ' has-error' : '' ?>">
					<?php if (get_option('use_recaptcha', false) === true): ?>
							<?php echo $captcha ?>
					<?php else: ?>
						<div class="row">
							<div class="col-xs-12 col-sm-6"><?php echo $captcha_image ?></div>
							<div class="col-xs-12 col-sm-6"><?php echo print_input($captcha, array('class' => 'form-control')) ?></div>
						</div>
					<?php endif; ?>
						<?php echo form_error('captcha', '<small class="help-block">', '</small>') ?>
					</div>
				<?php endif; ?>
					<button type="submit" class="btn btn-primary btn-block"><?php _e('lang_send_link') ?></button>
				<?php echo form_close() ?>
			</div>
			<div class="panel-footer">
					<?php echo anchor('login', __('lang_login'), 'class="btn btn-default"') ?>
				<?php if (get_option('allow_registration', false) === true): ?>
					<?php echo anchor('register', __('lang_create_account'), 'class="btn btn-default pull-right"') ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
