<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Form Helpers
 * 
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category 	Helpers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

/*------------------------------------------------------------------------*/

function clean($string)
{
    $string = str_replace(' ', '-', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);

    return preg_replace('/-+/', '-', $string);
}