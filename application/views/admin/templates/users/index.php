<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

add_script(get_theme_path('assets/js/users.js'));
?>

<div class="table-responsive">
    <table class="table table-hover table-striped mb-0">
        <thead>
            <tr>
                <th class="w-5">ID</th>
                <th class="w-20"><?php _e('lang_fullname') ?></th>
                <th class="w-15"><?php _e('lang_username') ?></th>
                <th class="w-15"><?php _e('lang_email_address') ?></th>
                <th class="w-10"><?php _e('lang_users_role') ?></th>
                <th class="w-15"><?php _e('lang_status') ?></th>
                <th class="w-20 text-end"><?php _e('lang_actions') ?></th>
            </tr>
        </thead>
        <tbody id="users-list">
            <?php
			foreach (force_array($users) as $user):
				if ($user->id == $c_user->id && apply_filters('host_user', false)){
					continue;
				}
				if ($user->admin && $c_user->admin == false){
					continue;
				}
				?>
				<tr id="user-<?php echo $user->id; ?>" data-id="<?php echo $user->id; ?>"
					data-name="<?php echo $user->username; ?>">
					<?php

					// User's ID.
					echo html_tag('td', null, $user->id),

					// Full name, username and email address.
					html_tag('td', null, fa_icon($user->gender)
						.user_anchor($user->id, '', 'target="_blank"')
						.html_tag('div', ['class'=>'text-muted text-sm m-0'], $user->email)),
					html_tag('td', null, $user->username),
					html_tag('td', null, html_tag('span', ['class'=>'text-muted text-sm m-0'], date('Y-m-d / H:i', $user->created_at))),
					html_tag('td', null, __('lang_'.$user->subtype));

					// Status.
					echo '<td>';
					if ($user->enabled > 0) {
						echo html_tag('span', array(
							'class' => 'badge bg-success'
						), __('lang_active'));
					} else {
						echo html_tag('span', array(
							'class' => 'badge bg-warning'
						), __('lang_INACTIVE'));
					}

					if (0 <> $user->deleted) {
						echo html_tag('span', array(
							'class' => 'badge bg-danger ms-1'
						), __('lang_deleteD'));
					}
					echo '</td>';

					// User actions.
					echo '<td class="text-end">';
					echo '<div class="btn-group">';

						/**
						 * Fire before default users actions.
						 */
						do_action('admin_users_action', $user);

						// Edit user button.
						echo html_tag('a', array(
							'href'   => admin_url('users/edit/'.$user->id),
							'class'  => 'btn btn-warning btn-sm',
							'rel'    => 'tooltip',
							'title'  => __('lang_EDIT_USER'),
						), fa_icon('edit'));

						// Activate/deactivate user.
						if (1 == $user->enabled) {
							echo html_tag('button', array(
								'type'          => 'button',
								'data-endpoint' => esc_url(nonce_admin_url("users?action=deactivate&amp;user={$user->id}&amp;next=".rawurlencode($uri_string), "user-deactivate_{$user->id}")),
								'class'         => 'btn btn-outline-secondary btn-sm user-deactivate',
								'rel'           => 'tooltip',
								'title'         => __('lang_deactivate'),
							), fa_icon('lock'));
						} else {
							echo html_tag('button', array(
								'type'          => 'button',
								'data-endpoint' => esc_url(nonce_admin_url("users?action=activate&amp;user={$user->id}&amp;next=".rawurlencode($uri_string), "user-activate_{$user->id}")),
								'class'         => 'btn btn-outline-secondary btn-sm user-activate',
								'rel'           => 'tooltip',
								'title'         => __('lang_activate'),
							), fa_icon('unlock-alt text-success'));
						}

						// Already deleted?
						if (1 == $user->deleted)
						{
							echo html_tag('button', array(
								'type'          => 'button',
								'data-endpoint' => esc_url(nonce_admin_url("users?action=restore&amp;user={$user->id}&amp;next=".rawurlencode($uri_string), "user-restore_{$user->id}")),
								'class'         => 'btn btn-outline-secondary btn-sm user-restore',
								'rel'           => 'tooltip',
								'title'         => __('lang_RESTORE_USER'),
							), fa_icon('history'));
						} else
						{
							echo html_tag('button', array(
								'type'          => 'button',
								'data-endpoint' => esc_url(nonce_admin_url("users?action=delete&amp;user={$user->id}&amp;next=".rawurlencode($uri_string), "user-delete_{$user->id}")),
								'class'         => 'btn btn-outline-secondary btn-sm user-delete',
								'rel'           => 'tooltip',
								'title'         => __('lang_delete'),
							), fa_icon('times'));
						}

						echo html_tag('button', array(
							'type'          => 'button',
							'data-endpoint' => esc_url(nonce_admin_url("users?action=remove&amp;user={$user->id}&amp;next=".rawurlencode($uri_string), "user-remove_{$user->id}")),
							'class'         => 'btn btn-outline-danger btn-sm user-remove',
							'rel'           => 'tooltip',
							'title'         => __('lang_REMOVE_USER'),
						), fa_icon('trash'));

					echo '</div>';
					echo '</td>';

					?>
				</tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php
// Display the pagination.
echo $pagination;