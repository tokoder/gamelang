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
 * Separated dashboard header.
 */
echo get_partial('admin_header');

echo '<main class="wrapper container my-3" role="main">';

    // Display the alert.
    the_alert();

    // Display the page content.
    the_content();

echo '</main>';

/**
 * Separated dashboard footer.
 */
echo get_partial('admin_footer');