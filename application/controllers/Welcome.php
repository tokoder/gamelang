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
 * Welcome Controller Class
 *
 * It handles render front-end
 *
 * @subpackage 	Controllers
 * @author		Tokoder Team
 */
class Welcome extends CG_Controller
{
	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		parent::__construct();
	}

    // -----------------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->themes->render();
	}

    // -----------------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 */
	public function page_mising()
	{
		$this->themes
			->set_layout('clean')
			->set_view('welcome/404')
			->render();
	}
}