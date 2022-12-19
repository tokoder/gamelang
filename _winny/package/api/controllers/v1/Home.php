<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Home extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/*------------------------------------------------------------------------*/
	
	public function index()
	{
		redirect();
	}
}
