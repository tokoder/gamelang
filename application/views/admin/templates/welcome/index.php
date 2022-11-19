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

/**
 * Fires at the top of dashboard main page content.
 */
do_action('admin_index_header');

/**
 * Fires within the dashboard top stats cards.
 */
do_action('admin_index_stats');

/**
 * Fires within the dashboard main page content.
 */
do_action('admin_index_content');

/**
 * Fires below the dashboard main page content.
 */
do_action('admin_index_footer');