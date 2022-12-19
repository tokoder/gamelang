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
		if (isset($_GET['cat'])) {
			$this->data['url'] = $_GET['cat'];
	
			$this->view('kategori', $this->data);
		}
		else {
			$url = $_GET['url'];

			if (! isset($url) ) {
				redirect("404_index");
			}

			// GET PRODUK
			$produk = $this->produk_model->getProduk($url, 'semua', 'url');
			if (!$produk) {
				redirect("404_notfound");
				exit;
			}
	
			// REVIEW PRODUK
			$review = $this->sales_model->getReview($produk->id, 8);
	
			// VIEW PRODUK
			$this->data['titel']  = "Jual " . $produk->nama;
			$this->data['desc']   = strip_tags($produk->deskripsi);
			$this->data['img']    = $this->produk_model->getFoto($produk->id, "utama");
			$this->data['url']    = site_url("product?url=" . strtolower($produk->url));
			$this->data['review'] = $review;
			$this->data['data']   = $produk;
	
			$this->view('produk', $this->data);
		}
	}
}
