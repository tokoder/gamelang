<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="nav-item"><a href="#sysinfo" class="nav-link active" aria-controls="sysinfo" role="tab" data-bs-toggle="tab"><?php _e('lang_system_information'); ?></a></li>
	<?php if (ENVIRONMENT !== 'production') : ?>
	<li role="presentation" class="nav-item"><a href="#phpset" class="nav-link" aria-controls="phpset" role="tab" data-bs-toggle="tab"><?php _e('lang_php_settings'); ?></a></li>
	<li role="presentation" class="nav-item"><a href="#phpinfo" class="nav-link" aria-controls="phpinfo" role="tab" data-bs-toggle="tab"><?php _e('lang_php_info'); ?></a></li>
	<?php endif; ?>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="box box-borderless tab-pane active" id="sysinfo">
		<div class="table-responsive-sm">
			<table class="table table-hover table-striped">
				<thead><tr><th class="w-25"><?php _e('lang_setting'); ?></th><th><?php _e('lang_value'); ?></th></tr></thead>
				<tbody>
				<?php foreach ($info as $i_key => $i_val): ?>
					<tr><th><?php _e('lang_'.strtoupper($i_key)); ?></th><td><?php echo $i_val; ?></td></tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div role="tabpanel" class="box box-borderless tab-pane" id="phpset">
		<div class="table-responsive-sm">
			<table class="table table-hover table-striped">
				<thead><tr><th class="w-25"><?php _e('lang_setting'); ?></th><th><?php _e('lang_value'); ?></th></tr></thead>
				<tbody>
				<?php foreach ($php as $p_key => $p_val): ?>
					<tr>
						<th><?php _e('lang_'.strtoupper($p_key)); ?></th>
						<td><?php
						switch ($p_val) {
							case '1':
								_e('lang_on');
								break;
							case '0':
								_e('lang_off');
								break;
							case null:
							case empty($p_val):
								_e('lang_none');
								break;
							default:
								echo $p_val;
								break;
						}
						?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div role="tabpanel" class="box box-borderless tab-pane" id="phpinfo">
		<div class="table-responsive-sm">
			<?php echo $phpinfo; ?>
		</div>
	</div>
</div>
