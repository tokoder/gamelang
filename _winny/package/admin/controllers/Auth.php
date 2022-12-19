<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Auth extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');

		$this->_partial = array(
			'head',
			'body',
			'foot'
		);
		
		$this->data['set'] = $this->setting->getSetting("semua");
	}
    
    /*------------------------------------------------------------------------*/
    
	public function index()
	{
		if (isset($_POST["username"])) {
			redirect("404_nf");
		} else {
			$this->_script = 'auth/_script';
			$this->view("auth/login", $this->data);
		}
	}

    /*------------------------------------------------------------------------*/
    
	public function login()
	{
		if (isset($_POST["username"])) {
			//$this->session->sess_destroy();

			$this->db->where("username", $_POST["username"]);
			$db = $this->db->get("user_admin");

			if ($db->num_rows() > 0) {
				foreach ($db->result() as $r) {
					if ($_POST["pass"] == $this->setting->decode($r->password)) {

						// Set Session data
						$sessionLogin = array(  
							'uid'    => $r->id,
							'gid'    => $r->level,
							'isMasok' => true,
						);
						$this->session->set_userdata($sessionLogin);

						echo json_encode(array(
							"success" => true, 
							"name" => $_POST["username"], 
							"token" => $this->security->get_csrf_hash()));
					} else {
						echo json_encode(array(
							"success" => false, 
							"token" => $this->security->get_csrf_hash()));
					}
				}
			} else {
				echo json_encode(array(
					"success" => false, 
					"token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array(
				"success" => false));
		}
	}

    /*------------------------------------------------------------------------*/
    
	public function logout()
	{
		$this->session->sess_destroy();
		redirect("admin/auth");
	}
}