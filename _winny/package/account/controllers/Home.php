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
	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->library('rajaongkir');
		
		$this->data['set'] = $this->setting->getSetting("semua");
	}

	/*------------------------------------------------------------------------*/

	/**
	 * Index Page
	 */
	public function index()
	{
		// Cek Login
		if ($this->setting->cekLogin() == true) {
			// Cek Status
			if ( isset($_SESSION["status"]) && $_SESSION["status"] == 1) {
				// Get data rekening
				$this->data['titel'] = "Akun Saya";
				$this->data['rekening'] = $this->user_model->getRekeningById($_SESSION["uid"]);
				$this->data['rekening_bank'] = $this->db->get("@rekening_bank");
				$this->data['profil'] = $this->user_model->getProfil($_SESSION["uid"], "semua", "usrid");
				$this->data['user'] = $this->user_model->getUser($_SESSION["uid"], "semua");
				if ($this->data['user'] == false) {
					$this->session->sess_destroy();
					redirect("auth");
					exit;
				}

				$this->view("setting", $this->data);
			} else {
				$this->session->sess_destroy();
				redirect("auth");
				exit;
			}
		} else {
			redirect("auth");
		}
	}
}
