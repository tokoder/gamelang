<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Variasi Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Variasi extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
		$this->load->helper('admin');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function index()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_GET['load']) and $_GET['load'] == "warna") {
			$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
			$perpage = (isset($_GET["perpage"]) and $_GET["perpage"] != "") ? $_GET["perpage"] : 10;
			$cari = (isset($_GET["cari"]) and $_GET["cari"] != "") ? $_GET["cari"] : "";

			$where = "nama LIKE '%$cari%'";
			$this->db->where($where);
			$row = $this->db->get("produk_variasiwarna");

			$this->db->where($where);
			$this->db->limit($perpage, ($page - 1) * $perpage);
			$this->db->order_by("id", "DESC");
			$db = $this->db->get("produk_variasiwarna");

			echo "
				<table class='table'>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
			";
			if ($row->num_rows() == 0) {
				echo "
						<tr>
							<th class='text-center text-danger' colspan=4>Belum ada variasi.</th>
						</tr>
				";
			}
			$no = 1 + (($page - 1) * $perpage);
			foreach ($db->result() as $r) {
				echo "
					<tr>
						<td>$no</td>
						<td>" . strtoupper($r->nama) . "</td>
						<td>
							<a href='javascript:void(0)' onclick='editWarna(" . $r->id . ",\"" . $r->nama . "\")' class='btn btn-primary btn-xs'><i class='fas fa-pencil-alt'></i></a>
							<a href='javascript:void(0)' onclick='hapusWarna(" . $r->id . ")' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
						</td>
					</tr>
				";
				$no++;
			}
			echo "
				</table>
			";
			echo $this->setting->createPagination($row->num_rows(), $page, $perpage, "refreshWarna");
		} elseif (isset($_GET['load']) and $_GET['load'] == "size") {
			$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
			$perpage = (isset($_GET["perpage"]) and $_GET["perpage"] != "") ? $_GET["perpage"] : 10;
			$cari = (isset($_GET["cari"]) and $_GET["cari"] != "") ? $_GET["cari"] : "";

			$where = "nama LIKE '%$cari%'";
			$this->db->where($where);
			$row = $this->db->get("produk_variasisize");

			$this->db->where($where);
			$this->db->limit($perpage, ($page - 1) * $perpage);
			$this->db->order_by("id", "DESC");
			$db = $this->db->get("produk_variasisize");

			echo "
				<table class='table'>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
			";
			if ($row->num_rows() == 0) {
				echo "
						<tr>
							<th class='text-center text-danger' colspan=4>Belum ada variasi.</th>
						</tr>
				";
			}
			$no = 1 + (($page - 1) * $perpage);
			foreach ($db->result() as $r) {
				echo "
					<tr>
						<td>$no</td>
						<td>" . strtoupper($r->nama) . "</td>
						<td>
							<a href='javascript:void(0)' onclick='editSize(" . $r->id . ",\"" . $r->nama . "\")' class='btn btn-primary btn-xs'><i class='fas fa-pencil-alt'></i></a>
							<a href='javascript:void(0)' onclick='hapusSize(" . $r->id . ")' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
						</td>
					</tr>
				";
				$no++;
			}
			echo "
				</table>
			";
			echo $this->setting->createPagination($row->num_rows(), $page, $perpage, "refreshSize");
		} else {
			$this->view('produk/variasi', ["menu" => 8]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function tambah($id = 0)
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		$_POST["id"] = isset($_POST["id"]) ? clean($_POST["id"]) : 0;
		if (isset($_POST["jenis"]) and $_POST["jenis"] == "warna") {
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"usrid"	=> $_SESSION["uid"]
			];

			if ($_POST["id"] > 0) {
				$this->db->where("id", $_POST["id"]);
				$this->db->update("produk_variasiwarna", $data);
			} else {
				$this->db->insert("produk_variasiwarna", $data);
				$insertid = $this->db->insert_id();
			}
			echo json_encode([
				"success" => true, 
				"token" => $this->security->get_csrf_hash()]);
		} elseif (isset($_POST["jenis"]) and $_POST["jenis"] == "size") {
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"usrid"	=> $_SESSION["uid"]
			];

			if ($_POST["id"] > 0) {
				$this->db->where("id", $_POST["id"]);
				$this->db->update("produk_variasisize", $data);
			} else {
				$this->db->insert("produk_variasisize", $data);
				$insertid = $this->db->insert_id();
			}

			echo json_encode([
				"success" => true, 
				"token" => $this->security->get_csrf_hash()]);
		} else {
			echo json_encode([
				"success" => false, 
				"token" => $this->security->get_csrf_hash()]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function hapus()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_POST["theid"])) {
			$_POST["theid"] = clean($_POST["theid"]);
			$this->db->where("id", $_POST["theid"]);
			if ($this->db->delete("produk_variasi")) {
				echo json_encode([
					"success" => true, 
					"token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode([
					"success" => false, 
					"token" => $this->security->get_csrf_hash()]);
			}
		} else {
			echo json_encode([
				"success" => false]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function warna()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_POST["theid"])) {
			$_POST["theid"] = clean($_POST["theid"]);
			$this->db->where("id", $_POST["theid"]);
			if ($this->db->delete("produk_variasiwarna")) {
				echo json_encode([
					"success" => true, 
					"token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode([
					"success" => false, 
					"token" => $this->security->get_csrf_hash()]);
			}
		} else {
			echo json_encode([
				"success" => false]);
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function size()
	{
		if (!isset($_SESSION["isMasok"])) {
			redirect("admin/auth");
			exit;
		}

		if (isset($_POST["theid"])) {
			$_POST["theid"] = clean($_POST["theid"]);
			$this->db->where("id", $_POST["theid"]);
			if ($this->db->delete("produk_variasisize")) {
				echo json_encode([
					"success" => true, 
					"token" => $this->security->get_csrf_hash()]);
			} else {
				echo json_encode([
					"success" => false, 
					"token" => $this->security->get_csrf_hash()]);
			}
		} else {
			echo json_encode([
				"success" => false]);
		}
	}
}