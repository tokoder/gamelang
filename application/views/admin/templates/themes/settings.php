<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

// We simply do the action.
do_action('theme_settings_'.$theme['folder'], $theme);
