<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Search Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Search extends MY_Controller
{
	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('produk_model');
		$this->load->model('sales_model');
		$this->data['set'] = $this->setting->getSetting("semua");
	}

	/*------------------------------------------------------------------------*/
	
	public function index()
	{
		$this->load->model('produk_model');

		$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($_GET["orderby"]) and $_GET["orderby"] != "") ? $_GET["orderby"] : "stok DESC, tglupdate DESC";
		$cari = (isset($_GET["cari"]) and $_GET["cari"] != "") ? $_GET["cari"] : "";
		$perpage = 12;

		$where = "(produk.nama LIKE '%" . $cari . "%' OR produk.deskripsi LIKE '%" . $cari . "%' OR produk.harga LIKE '%" . $cari . "%')";
		$rows = $this->produk_model->getProdukBySearch($where);
		$rows = $rows->num_rows();

		$this->data["perpage"] = $perpage;
		$this->data["produk"]  = $this->produk_model->getProdukBySearch($where, [], $orderby, $perpage, $page);
		$this->data["rows"]    = $rows;
		$this->data["page"]    = $page;
		$this->data["query"]   = $cari;
		$this->data['titel']   = "Hasil pencarian untuk '" . $cari . "'";

		$this->view('search', $this->data);
	}
}
