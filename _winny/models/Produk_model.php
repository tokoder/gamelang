<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_model extends CI_Model
{
	private $tabel_produk       = "produk";
	private $tabel_variasi      = "produk_variasi";
	private $tabel_upload       = "produk_upload";
	private $tabel_variasiwarna = "produk_variasiwarna";
	private $tabel_variasisize  = "produk_variasisize";

	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**------------------------------------------------------------------------
	 *                           PRODUK
	 *------------------------------------------------------------------------**/
	// GET
	function getProduk($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_produk);

		if ($what == "semua") {
			$result = null;
			if ($res->num_rows() > 0) {
				foreach ($res->result() as $key => $value) {
					$result[$key] = $value;
				}
				$result = $result[0];
			}
		} else {
			$result = null;
			foreach ($res->result() as $re) {
				if ($what == "harga") {
					$level = isset($_SESSION["gid"]) ? $_SESSION["gid"] : "";
					if ($level == 3) {
						$result = $re->hargaagen;
					} elseif ($level == 2) {
						$result = $re->hargareseller;
					} else {
						$result = $re->harga;
					}
				} else {
					$result = $re->$what;
				}
			}
		}
		return $result;
	}
	// GET PRODUK ORDER
	public function getProdukOrder()
	{
		$this->db->order_by("stok DESC,tglupdate DESC");
		$db = $this->db->get($this->tabel_produk);

		return $db;
	}
	// GET PRODUK BY CAT
	public function getProdukByCat($cat, $id)
	{
		$this->db->where("idcat", $cat);
		$this->db->where("id!=", $id);
		$this->db->limit(12);
		$this->db->order_by("id", "RANDOM");
		$db = $this->db->get($this->tabel_produk);

		return $db;
	}
	// BY SEARCH
	public function getProdukBySearch($where = null, $notin = [], $order = null, $limit = null, $offset = null)
	{
		$this->db->where($where);
		if (count($notin) > 0) {
			$this->db->where_not_in($notin);
		}
		if($limit) {
			$this->db->order_by($order);
			$this->db->limit($limit, ($offset - 1) * $limit);
		}
		return $this->db->get($this->tabel_produk);
	}
	// UPDATE
	public function updateProduk($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->update($this->tabel_produk, $data);
	}

	/**------------------------------------------------------------------------
	 *                           UPLOAD
	 *------------------------------------------------------------------------**/
	// Get Foto PRODUK
	function getFoto($id, $kat = "utama")
	{
		$path = 'uploads/produk/';
		$fpath = FCPATH.$path;
		$server = site_url($path);
		$this->db->where("idproduk", $id);
		if ($kat == "utama") {
			$this->db->where("jenis", 1);
		}
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_upload);

		$result = "default.png";
		foreach($res->result() as $re){
			$result = $re->nama;
		}

		if ( file_exists($fpath.$result) ) {
			return $server.$result;
		}
		else {
			return $server."default.png";
		}
	}

	/**------------------------------------------------------------------------
	 *                           TOKO
	 *------------------------------------------------------------------------**/
	// GET
	function getProdukByToko($id_toko, $order, $limit, $offset)
	{
		$this->db->where("idtoko", $id_toko);
		if($limit) {
			$this->db->order_by($order, "desc");
			$this->db->limit($limit, ($offset - 1) * $limit);
		}
		$result = $this->db->get($this->tabel_produk);
		return $result;
	}
	
	/**------------------------------------------------------------------------
	 **                            VARIASI
	 *------------------------------------------------------------------------**/
	// UPDATE
	public function updateVariasi($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->update($this->tabel_variasi, $data);
	}
	// GET
	function getVariasi($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get( $this->tabel_variasi );

		if ($what == "semua") {
			$result = array();
			if ($res->num_rows() > 0) {
				foreach ($res->result() as $key => $value) {
					$result[$key] = $value;
				}
				$result = $result[0];
			}
		} else {
			$result = null;
			foreach ($res->result() as $re) {
				$result = $re->$what;
			}
		}
		return $result;
	}
	// GET WHERE
	public function getVariasiWhere($id)
	{
		$this->db->where("idproduk", $id);
		$dbv = $this->db->get( $this->tabel_variasi );
		
		return $dbv;
	}

	public function getVariasiSum()
	{
		$this->db->select("SUM(stok) AS stok,idproduk");
		$this->db->group_by("idproduk");
		return $this->db->get($this->tabel_variasi);
	}
	
	/**
	 * GET VARIASI WARNA
	 * 
	 * @return void
	 */
	function getWarna($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get( $this->tabel_variasiwarna );

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
	
	/**
	 * GET VARIASI SIZE
	 * 
	 * @return void
	 */
	function getSize($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get( $this->tabel_variasisize );

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
}
