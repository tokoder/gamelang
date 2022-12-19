<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
	private $tabel_user          = "user";
	private $tabel_userotpdaftar = "user_otpdaftar";
	private $tabel_userotplogin  = "user_otplogin";
	private $tabel_profile       = "user_profil";
	private $tabel_saldo_history = "user_saldo_history";
	private $tabel_saldo_tarik   = "user_saldo_tarik";
	private $tabel_saldo_darike   = "user_saldo_darike";
	private $tabel_saldo         = "user_saldo";
	private $tabel_rekening      = "user_rekening";
	private $tabel_alamat      = "user_alamat";

	/**
	 * Construct for this controller.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/*-------------------------------- USERS ------------------------------*/
	
	function getUserAdmin($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("user_admin");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	// GET
	public function getUser($id, $what, $where = "id")
	{
		$this->db->where($where, $id);
		$this->db->limit(1);
		$res = $this->db->get( $this->tabel_user );

		if ($what == "semua") {
			$result = array(0);
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
	// GET BY EMAIL
	public function cek_user_by_email($email)
	{
		$this->db->where("username", $email);
		$this->db->or_where("nohp", $email);
		$this->db->limit(1);
		$user = $this->db->get( $this->tabel_user );

		return $user;
	}
	// SAVE
	public function save($data)
	{				
		$this->db->insert( $this->tabel_user, $data);

		return $this->db->insert_id();
	}
	// UPDATE
	public function update($id, $data)
	{				
		$this->db->where("id", $id);
		$this->db->update( $this->tabel_user, $data);
	}
	
	/*-------------------------------- OTP DAFTAR ------------------------------*/
	
	public function saveOtp($data)
	{				
		$this->db->insert( $this->tabel_userotpdaftar, $data);

		return $this->db->insert_id();
	}
	public function getOtp($id)
	{				
		$this->db->where("id", $id);
		$db = $this->db->get( $this->tabel_userotpdaftar);

		return $db;
	}
	public function updateOtp($id, $data)
	{				
		$this->db->where("id", $id);
		$this->db->update( $this->tabel_userotpdaftar, $data);
	}
	
	/*-------------------------------- OTP LOGIN ------------------------------*/
	
	public function saveOtpLogin($data)
	{				
		$this->db->insert( $this->tabel_userotplogin, $data);

		return $this->db->insert_id();
	}
	public function getOtpLogin($id)
	{				
		$this->db->where("id", $id);
		$db = $this->db->get( $this->tabel_userotplogin);

		return $db;
	}
	public function updateOtpLogin($id, $data)
	{				
		$this->db->where("id", $id);
		$this->db->update( $this->tabel_userotplogin, $data);
	}

	/*-------------------------------- PROFILE ------------------------------*/
	
	// SAVE
	public function saveProfil($data)
	{				
		$this->db->insert( $this->tabel_profile, $data);
		return $this->db->insert_id();
	}
	// UPDATE
	public function updateProfil($id, $data, $opo = "id")
	{				
		$this->db->where($opo, $id);
		$this->db->update( $this->tabel_profile, $data);
	}
	// GET
	public function getProfil($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get( $this->tabel_profile );

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

	/*-------------------------------- SALDO ------------------------------*/
	
	/**
	 * SALDO HISTORY
	 * 
	 * @return void
	 */
	// GET SALDO HISTORY
	public function getSaldoHistoryByID($id, $orderby = false, $perpage = false, $page = false)
	{
		$this->db->from($this->tabel_saldo_history);
		$this->db->where("usrid", $id);
		if ($perpage) {
			$this->db->order_by($orderby, "desc");
			$this->db->limit($perpage, ($page - 1) * $perpage);
		}

		return $this->db->get();
	}
	// SAVE
	public function saveSaldoHistory($data)
	{
		$this->db->insert($this->tabel_saldo_history, $data);
	}

	/**
	 * SALDO TARIK
	 * 
	 * @return void
	 */
	// GET SALDO TARIK
	public function getSaldoTarikByID($id, $jenis, $orderby = false, $perpage = false, $page = false)
	{
		$this->db->from($this->tabel_saldo_tarik);
		$this->db->where("usrid", $id);
		$this->db->where("jenis", $jenis);
		if ($perpage) {
			$this->db->order_by($orderby, "desc");
			$this->db->limit($perpage, ($page - 1) * $perpage);
		}

		return $this->db->get();
	}
	function getSaldotarik($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_saldo_tarik);

		if ($what == "semua") {
			$result = array();
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

	/**
	 * SALDO DARI KE
	 * 
	 * @return void
	 */
	function getSaldodarike($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_saldo_darike);

		if ($what == "semua") {
			$result = array();
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

	/**
	 * SALDO
	 *
	 * @param null $data
	 * @return void
	 */
	// SAVE
	public function saveSaldo($data)
	{
		$this->db->insert($this->tabel_saldo, $data);
		return $this->db->insert_id();
	}
	// GET SALDO
	function getSaldo($id, $what, $opo = "id")
	{
		$this->db->where($opo, $id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_saldo);

		if ($what == "semua") {
			$result = array();
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
	// UPDATE
	public function updateSaldo($id, $data)
	{
		$this->db->where("usrid", $id);
		$this->db->update($this->tabel_saldo, $data);
	}

	/*-------------------------------- REKENING ------------------------------*/
	// GET REKENING BY ID
	public function getRekeningJoin($id = 0)
	{
		$this->db->select( '*, a.id as idnyabank' );
		$this->db->from($this->tabel_rekening);
		$this->db->where('usrid', $id);
		$this->db->join('@rekening_bank a', 'a.id = '.$this->tabel_rekening.'.idbank');
		return $this->db->get();
	}
	// GET REKENING BY ID
	public function getRekeningById($id)
	{
		$this->db->where("usrid", $id);
		return $this->db->get($this->tabel_rekening);
	}
	// GET REKENING
	public function getRekening($id,$what,$opo="id")
	{
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get($this->tabel_rekening);

		if($what == "semua"){
			$result = array();
		if($res->num_rows() > 0){
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	// UPDATE
	public function updateRekening($id, $data)
	{
		$this->db->where("id", $id);
		$this->db->update($this->tabel_rekening, $data);
	}
	// SAVE
	public function saveRekening($data)
	{
		$this->db->insert($this->tabel_rekening, $data);
		return $this->db->insert_id();
	}
	// DELETE
	public function deleteRekening($id)
	{
		$this->db->where("id", $id);
		$this->db->delete($this->tabel_rekening);
	}
}
