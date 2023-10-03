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

/**
 * Email footer powered by text.
 */
$powered_by = sprintf(__('powered_by %s'), anchor(site_url(), get_option('site_name'), 'target="_blank"'));
$powered_by = apply_filters('email_powered_by', $powered_by);

$email_footer = <<<EOT
						</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

						<div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
							<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">{$powered_by}</table>
						</div>

					</div>
				</td>
				<td style="vertical-align: top;">&nbsp;</td>
			</tr>
		</table>
	</body>
</html>
EOT;

/**
 * Filters the default emails footer.
 */
echo  apply_filters('email_footer', $email_footer);
