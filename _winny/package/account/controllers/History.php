<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * History Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class History extends MY_Controller
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
	
	function tarik()
	{
		$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) and $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$rows = $this->user_model->getSaldoHistoryByID($_SESSION["uid"]);
		$rows = $rows->num_rows();

		$this->data["saldo"] = $this->user_model->getSaldoHistoryByID($_SESSION["uid"], $orderby, $perpage, $page);
		$this->data["rows"] = $rows;
		$this->data["perpage"] = $perpage;
		$this->data["page"] = $page;

		$this->view("history/tarik", $this->data);
	}

	/*------------------------------------------------------------------------*/
	
	function topup()
	{
		$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) and $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$rows = $this->user_model->getSaldoTarikByID($_SESSION["uid"], 2);
		$rows = $rows->num_rows();

		$this->data["saldo"] = $this->user_model->getSaldoTarikByID($_SESSION["uid"], 2, $orderby, $perpage, $page);
		$this->data["rows"] = $rows;
		$this->data["perpage"] = $perpage;
		$this->data["page"] = $page;

		$this->view("history/topup", $this->data);
	}
}
