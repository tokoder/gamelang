<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Setting Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Setting extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}

    /*------------------------------------------------------------------------*/

	public function form()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		$this->load->library('rajaongkir');
		$this->load->view("setting/form");
	}

	/*------------------------------------------------------------------------*/

	public function kurir()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		$this->load->view("setting/kurir");
	}

	/*------------------------------------------------------------------------*/

	public function server()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		$this->load->view("setting/server");
	}

    /*------------------------------------------------------------------------*/

	public function wasap()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_GET["load"])) {
			$this->load->view("setting/wasap");
		} elseif (isset($_POST["formid"])) {
			//$_POST["formid"] = intval(["formid"]);
			$rek = $this->setting->getWasap(intval($_POST["formid"]), "semua");
			$data = [
				"id"	=> $_POST["formid"],
				"nama"	=> $rek->nama,
				"wasap"	=> $rek->wasap,
				"token"	=> $this->security->get_csrf_hash()
			];
			echo json_encode($data);
		} else {
			redirect("admin");
		}
	}

    /*------------------------------------------------------------------------*/

	public function rekening()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/login");
			exit;
		}

		if (isset($_GET["load"])) {
			$this->load->view("setting/rekening");
		} elseif (isset($_POST["formid"])) {
			//$_POST["formid"] = intval(["formid"]);
			$rek = $this->setting->getRekening(intval($_POST["formid"]), "semua");
			$data = [
				"id"		=> $_POST["formid"],
				"pass"		=> $this->setting->decode($rek->pass),
				"userid"	=> $rek->userid,
				"atasnama"	=> $rek->atasnama,
				"idbank"	=> $rek->idbank,
				"norek"		=> $rek->norek,
				"kcp"		=> $rek->kcp,
				"token"		=> $this->security->get_csrf_hash()
			];
			echo json_encode($data);
		} else {
			redirect("admin");
		}
	}
}