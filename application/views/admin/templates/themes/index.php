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

add_script(get_theme_path('assets/js/themes.js'));

$theme_item_temp =<<<EOT
<div class="col-sm-6 col-md-4 theme-item mb-3" id="theme-{folder}" data-name="{name}">
	<div class="card theme-inner h-100">
		<img src="{screenshot}" alt="{name}" class="theme-screenshot img-fluid" />
		<div class="theme-caption clearfix p-2 mt-auto">
			<h3 class="theme-title m-0">{name}<span class="theme-action float-end">{actions}</span></h3>
		</div>
	</div>
</div>
EOT;

if ($themes)
{
	echo '<div class="container">';
	echo '<div class="row" id="themes-list">';
	foreach ($themes as $folder => $t) {
		$t['actions'] = implode('', $t['actions']);
		echo str_replace(
			array('{folder}', '{name}', '{screenshot}', '{actions}'),
			array($folder, $t['name'], $t['screenshot'], $t['actions']),
			$theme_item_temp
		);
	}
	echo '</div>';
	echo '</div>';
}

echo '<div id="theme-details">';

if (isset($theme) && null !== $theme): ?>
<div class="modal fade" tabindex="-1" role="dialog" id="theme-modal" tabindex="-1">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header clearfix">
				<h4 class="modal-title"><?php printf(__('lang_detail_name'), $theme['name']); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-7">
						<img src="<?php echo $theme['screenshot']; ?>" alt="<?php echo $theme['name']; ?>" class="img-fluid" data-action="zoom">
					</div>
					<div class="col-sm-12 col-md-5">
						<h4 class="page-header clearfix">
							<?php echo $theme['name_uri']; ?>
							<small class="text-muted"><?php echo $theme['version']; ?></small>
							<small class="float-end"><?php echo label_condition($theme['enabled'], 'lang:lang_active', ''); ?></small>
						</h4>
						<p><?php echo $theme['description']; ?></p><br />
						<div class="table-responsive-sm">
							<table class="table table-condensed table-striped">
								<tr><th class="w-35"><?php _e('lang_AUTHOR'); ?></th><td><?php echo $theme['author']; ?></td></tr>
								<?php if ($theme['author_email']): ?>
								<tr><th><?php _e('lang_author_email'); ?></th><td><?php echo $theme['author_email']; ?></td></tr>
								<?php endif; ?>
								<tr><th><?php _e('lang_license'); ?></th><td><?php echo $theme['license']; ?></td></tr>
								<tr><th><?php _e('lang_tags'); ?></th><td><?php echo $theme['tags']; ?></td></tr>
							</table>
						</div>
						<?php if (true !== $theme['enabled']): ?>
						<p class="clearfix"><?php echo $theme['action_activate'], $theme['action_delete']; ?></p>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif;

echo '</div>';
