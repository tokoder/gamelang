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
echo $this->themes->get_partial('admin_header');

echo '<main class="wrapper container my-3" role="main">';

    // Display the alert.
    echo $this->themes->get_alert();

    echo '<div id="wrapper">';

        /**
         * Fires at the top of page content.
         */
        do_action('admin_page_header');

        // Display the page content.
        echo $this->themes->print_content();

        /**
         * Fires at the end of page content.
         */
        do_action('admin_page_footer');

    echo '</div>';

echo '</main>';

/**
 * Separated dashboard footer.
 */
echo $this->themes->get_partial('admin_footer');