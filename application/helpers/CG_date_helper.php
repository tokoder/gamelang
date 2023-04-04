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

require APPPATH . "third_party/carbon/vendor/autoload.php";

/**
 * Gamelang date Helpers
 *
 * @category 	Helper
 * @author		Tokoder Team
 */

// -----------------------------------------------------------------------------

use Carbon\Carbon;

if(!function_exists('diffForHumans'))
{
    function diffForHumans($date1)
    {
        // lang
        $lang = get_instance()->lang->lang_detail('locale');

        // count days
        return Carbon::parse(date('Y-m-d H:i', $date1))->locale($lang)->diffForHumans();
    }
}

//date format
if (!function_exists('formatted_date'))
{
    function formatted_date($datetime)
    {
        $date = date("M j, Y", $datetime);
        return $date;
    }
}