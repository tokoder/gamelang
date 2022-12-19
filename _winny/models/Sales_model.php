<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_model extends CI_Model
{
	private $tabel_sale        = "sales";
	private $tabel_sale_produk = "sales_produk";
	private $tabel_review      = "sales_review";
	private $tabel_wishlist    = "sales_wishlist";
	private $tabel_preorder    = "preorder";
	private $tabel_voucher    = "sales_voucher";

	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('produk_model');
		$this->load->model('sales_model');
	}
	function getJmlPesanan(){
		$this->db->where("status<=",1);
		$db = $this->db->get("sales");
		
		return $db->num_rows();
	}

	/**------------------------------------------------------------------------
	 *                           SALES
	 *------------------------------------------------------------------------**/
	function getTransaksi($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_sale);

		if ($what == "semua") {
			$result = array();
			foreach ($res->result() as $key => $value) {
				$result[$key] = $value;
			}
			$result = $result[0];
		} else {
			$result = null;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	// SAVE
	public function saveTransaksi($data)
	{
		$this->db->insert($this->tabel_sale, $data);
		return $this->db->insert_id();
	}
	// UPDATE
	public function updateTransaksi($where, $data)
	{
		if (is_array($where)) {
			foreach ($where as $key => $value) {
				$this->db->where($key, $value);
			}
		} else {
			$this->db->where("id", $where);
		}
		$this->db->update($this->tabel_sale, $data);
	}

	/**------------------------------------------------------------------------
	 *                           SALES ITEM PRODUK
	 *------------------------------------------------------------------------**/
	// SAVE
	public function saveSale($data)
	{
		$this->db->insert($this->tabel_sale_produk, $data);
		return $this->db->insert_id();
	}
	// UPDATE
	public function updateSale($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->update($this->tabel_sale_produk, $data);
	}
	// DELETE
	public function deleteSale($id)
	{
		$this->db->where("id", $id);
		$this->db->delete($this->tabel_sale_produk);
	}
	// GET
	function getSaleByCart($num = true)
	{
		// if ($this->cekLogin() == true) {
		// } else {
		// 	return 0;
		// }
		$this->db->where("idtransaksi", 0);
		$this->db->where("usrid", $_SESSION["uid"]);
		$db = $this->db->get($this->tabel_sale_produk);
		if ($num) {
			return $db->num_rows();
		} else {
			return $db;
		}
	}
	// GET
	function getSaleByID()
	{
		$this->db->where("usrid", $_SESSION["uid"]);
		$this->db->where("idtransaksi", 0);
		$this->db->where("idpo", 0);
		return $this->db->get($this->tabel_sale_produk);
	}
	// GET
	function getSaleByTransaksi($idtransaksi)
	{
		$this->db->where("idtransaksi", $idtransaksi);
		return $this->db->get($this->tabel_sale_produk);
	}
	// GET
	function getSale()
	{
		$this->db->order_by("RAND()");
		$this->db->limit(1);
		return $this->db->get($this->tabel_sale_produk);
	}
	// GET
	function getSaleProduk($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_sale_produk);

		if ($what == "semua") {
			$result = array();
			foreach ($res->result() as $key => $value) {
				$result[$key] = $value;
			}
			$result = $result[0];
		} else if ($what == "jmlTerjual") {
			$result = $res->num_rows();
		} else {
			$result = null;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	// Get Terjual Berapa
	function getTerjualBerapa($idproduk)
	{
		$this->db->where("idproduk", $idproduk);
		$db = $this->db->get($this->tabel_sale_produk);
		return $db->num_rows();
	}

	/**------------------------------------------------------------------------
	 *                           WISHLIST
	 *------------------------------------------------------------------------**/
	// GET
	function getWishlist($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_wishlist);

		if ($what == "semua") {
			if ($res->num_rows() == 0) {
				$result = array(0);
			}
			foreach ($res->result() as $key => $value) {
				$result[$key] = $value;
			}
			$result = $result[0];
		} else {
			$result = 0;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	// BY STATUS
	public function getWishlistStatus($where = null, $order = null, $limit = null, $offset = null)
	{
		$this->db->where($where);
		if($limit) {
			$this->db->order_by($order);
			$this->db->limit($limit, ($offset - 1) * $limit);
		}
		return $this->db->get($this->tabel_wishlist);
	}
	// CEK
	function cekWishlist($id)
	{
		$usrid = isset($_SESSION["uid"]) ? $_SESSION["uid"] : 0;
		$this->db->where("idproduk", $id);
		$this->db->where("usrid", $usrid);
		$res = $this->db->get($this->tabel_wishlist);

		if ($res->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	// GET COUNT
	function getWishlistCount()
	{
		$this->db->where("usrid", $_SESSION["uid"]);
		$res = $this->db->get($this->tabel_wishlist);

		return $res->num_rows();
	}
	// SIMPAN
	function saveWishlist($idproduk)
	{
		$data = array(
			"usrid"    => $_SESSION["uid"],
			"idproduk" => $idproduk,
			"tgl"      => date("Y-m-d H:i:s"),
			"status"   => 1
		);
		$this->db->insert($this->tabel_wishlist, $data);
		return $this->db->insert_id();
	}
	// HAPUS
	function deleteWishlist($idproduk)
	{
		$this->db->where("idproduk", $idproduk);
		$this->db->where("usrid", $_SESSION["uid"]);
		$this->db->delete($this->tabel_wishlist);
	}

	/**------------------------------------------------------------------------
	 **                            PREORDER
	 *------------------------------------------------------------------------**/
	// SAVE
	public function savePreorder($data)
	{
		$this->db->insert($this->tabel_preorder, $data);

		return $this->db->insert_id();
	}
	// GET
	public function getPreorder($id, $where = 'id')
	{
		$this->db->where($where, $id);
		$dbv = $this->db->get($this->tabel_preorder);

		return $dbv;
	}


	/**------------------------------------------------------------------------
	 *                           REVIEW
	 *------------------------------------------------------------------------**/
	// GET
	public function getReview($idproduk, $limit = null)
	{
		$this->db->where("idproduk", $idproduk);
		if ($limit) {
			$this->db->limit($limit);
			$this->db->order_by("nilai,id DESC");
		}
		$review = $this->db->get($this->tabel_review);
		return $review;
	}
	// SIMPAN ULASAN_REVIEW
	function tambahUlasan($ulasan)
	{
		for ($i = 0; $i < count($ulasan['nilai']); $i++) {
			$trx = $this->sales_model->getTransaksi($ulasan["orderid"][$i], "semua", "orderid");
			$data = array(
				"usrid"       => $_SESSION["uid"],
				"idproduk"    => $ulasan["produk"][$i],
				"idtransaksi" => $trx->id,
				"nilai"       => $ulasan['nilai'][$i],
				"tgl"         => date("Y-m-d H:i:s"),
				"keterangan"  => $ulasan["keterangan"][$i]
			);

			$this->db->insert($this->tabel_review, $data);
			return $this->db->insert_id();
		}
	}
	// Get Bintang
	function getBintang($idproduk = 0)
	{
		$this->db->where("idproduk", $idproduk);
		$res = $this->db->get($this->tabel_review);
		$count = 0;
		foreach ($res->result() as $r) {
			$count += $r->nilai;
		}
		$result = $count > 0 ? round($count / $res->num_rows(), 1) : 0;
		$result = ["nilai" => $result, "ulasan" => $res->num_rows()];
		return $result;
	}

	/*-------------------------------- VOUCHER ------------------------------*/
	
	// GET VOUCHERs
	function getVoucher($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_voucher);

		if ($what == "semua") {
			if ($res->num_rows() == 0) {
				$result = array(0);
			}
			foreach ($res->result() as $key => $value) {
				$result[$key] = $value;
			}
			$result = $result[0];
		} else {
			$result = 0;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getVoucherActive()
	{
		$this->db->where("mulai <=", date("Y-m-d"));
		$this->db->where("selesai >=", date("Y-m-d"));
		return $this->db->get($this->tabel_voucher);
	}
	
}
