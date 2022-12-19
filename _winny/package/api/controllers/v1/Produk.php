<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Produk Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Produk extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function hapus()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id", $_POST["id"]);
			$this->db->delete("produk");

			$this->db->where("idproduk", $_POST["id"]);
			$this->db->delete("produk_variasi");
			$this->db->where("idproduk", $_POST["id"]);
			$this->db->delete("produk_upload");

			echo json_encode(array("success" => true, "msg" => "berhasil menghapus", "token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array("success" => false, "msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function tambah()
	{
		if (isset($_POST)) {
			if ($_SESSION["uploadedPhotos"] != 0) {
				$tgl = date("Y-m-d H:i:s");
				$string = $this->clean($_POST["nama"]);
				$arr = $_POST;
				$arr2 = array(
					"tglbuat"	=> $tgl,
					"tglupdate"	=> $tgl,
					"url"		=> $string . "-" . date("His")
				);
				$data = array_merge($arr, $arr2);
				unset($data[$this->security->get_csrf_token_name()]);
				
				$this->db->insert("produk", $data);
				$insertid = $this->db->insert_id();

				if (isset($_SESSION["fotoProduk"]) and count($_SESSION["fotoProduk"]) > 0) {
					for ($i = 0; $i < count($_SESSION["fotoProduk"]); $i++) {
						$this->db->where("id", $_SESSION["fotoProduk"][$i]);
						$this->db->update("produk_upload", array("idproduk" => $insertid));
					}
					$this->session->unset_userdata("fotoProduk");
				}

				echo json_encode(array("success" => true, "msg" => "berhasil", "id" => $insertid, "token" => $this->security->get_csrf_hash()));
			} else {
				echo json_encode(array("success" => false, "msg" => "foto wajib di isi: " . $_SESSION["uploadedPhotos"], "token" => $this->security->get_csrf_hash()));
			}
		} else {
			echo json_encode(array("success" => false, "msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function update()
	{
		if (isset($_POST["id"])) {
			//$_POST["id"] = $this->clean($_POST["id"]);
			$tgl = date("Y-m-d H:i:s");
			$arr = $_POST;
			//$string = $this->clean($_POST["nama"]);
			$arr2 = array("tglupdate" => $tgl); //,"url"=>$string."-".date("His"));
			$data = array_merge($arr, $arr2);
			unset($data[$this->security->get_csrf_token_name()]);
			$this->db->where("id", intval($_POST["id"]));
			$this->db->update("produk", $data);

			echo json_encode(array("success" => true, "msg" => "berhasil", "id" => $_POST["id"], "token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array("success" => false, "msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function import()
	{
		if (isset($_POST)) {
			$config['upload_path'] = './import/';
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['file_name'] = "import_" . date("YmdHis");

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('fileupload')) {
				$error = $this->upload->display_errors();
				echo json_encode(array("success" => false, "msg" => $error, "token" => $this->security->get_csrf_hash()));
			} else {
				$uploadData = $this->upload->data();
				$inputFileName = FCPATH . "import/" . $uploadData["file_name"];

				if (!file_exists($inputFileName)) {
					echo json_encode(array("success" => false, "msg" => "file tidak ditemukan"));
					exit;
				}

				//$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//if(mime_content_type($inputFileName) !== null && in_array(mime_content_type($inputFileName), $file_mimes)) {
				$extension = pathinfo($inputFileName, PATHINFO_EXTENSION);

				if ('csv' == $extension) {
					$reader = new Csv();
				} else {
					$reader = new Xlsx();
				}

				$spreadsheet = $reader->load($inputFileName);

				$sheetData = $spreadsheet->getActiveSheet()->toArray();
				if (count($sheetData) > 1) {
					for ($i = 1; $i < count($sheetData); $i++) {
						$string = $this->clean($sheetData[$i]['1']);
						$data = array(
							"tglbuat"	=> date("Y-m-d H:i:s"),
							"tglupdate"	=> date("Y-m-d H:i:s"),
							"nama"		=> $sheetData[$i]['0'],
							"url"		=> $string . "-" . date("His"),
							"kode"		=> $sheetData[$i]['1'],
							"deskripsi"	=> $sheetData[$i]['2'],
							"idcat"		=> $sheetData[$i]['3'],
							"berat"		=> $sheetData[$i]['4'],
							"harga"		=> $sheetData[$i]['5'],
							"hargareseller"	=> $sheetData[$i]['6'],
							"hargaagen"		=> $sheetData[$i]['7'],
							"hargaagensp"	=> $sheetData[$i]['8'],
							"hargadistri"	=> $sheetData[$i]['9'],
							"minorder"	=> $sheetData[$i]['10'],
							"variasi"	=> $sheetData[$i]['11'],
							"subvariasi" => $sheetData[$i]['12'],
							"status"	=> $sheetData[$i]['13']
						);
						$this->db->insert("produk", $data);
						$idpro = $this->db->insert_id();

						for ($a = 1; $a <= 4; $a++) {
							$e = ($a - 1 > 0) ? 3 * ($a - 1) : 0;
							$nom = 14 + $e;
							if ($sheetData[$i][$nom] != "") {
								$var = array(
									"idproduk"	=> $idpro,
									"tgl"		=> date("Y-m-d H:i:s"),
									"warna"		=> $sheetData[$i][$nom],
									"size"		=> $sheetData[$i][$nom + 1],
									"stok"		=> $sheetData[$i][$nom + 2]
								);
								$this->db->insert("produk_variasi", $var);
							}
						}
					}
					echo json_encode(array("success" => true, "msg" => "berhasil mengimpor produk", "token" => $this->security->get_csrf_hash()));
				} else {
					echo json_encode(array("success" => false, "msg" => "file tidak sesuai atau kosong", "token" => $this->security->get_csrf_hash()));
				}
				//}else{
				//	echo json_encode(array("success"=>false,"msg"=>"file tidak sesuai: ".mime_content_type($inputFileName." | ".$inputFileName)));
				//}
			}
		} else {
			echo json_encode(array("success" => false, "msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function variasi($id = 0)
	{
		if (isset($_POST["id"])) {
			for ($i = 0; $i < count($_POST["id"]); $i++) {
				$data = [
					"idproduk"	=> $id,
					"warna"	=> $_POST["warna"][$i],
					"size"	=> $_POST["size"][$i],
					"stok"	=> $_POST["stok"][$i],
					"kuota"	=> $_POST["stok"][$i],
					"harga"	=> $_POST["harga"][$i],
					"hargareseller"	=> $_POST["hargareseller"][$i],
					"hargaagen"		=> $_POST["hargaagen"][$i],
					"hargaagensp"	=> $_POST["hargaagensp"][$i],
					"hargadistri"	=> $_POST["hargadistri"][$i],
					"tgl"	=> date("Y-m-d H:i:s")
				];

				if ($_POST["id"][$i] != 0) {
					if ($this->setting->getProduk($id, "preorder_id") > 0) {
						$this->db->where("variasi", $_POST["id"][$i]);
						$t = $this->db->get("preorder");
						$tot = 0;
						foreach ($t->result() as $r) {
							$tot += $r->jumlah;
						}

						if ($_POST["stok"][$i] < $tot) {
							echo json_encode(array("success" => false, "msg" => "stok variasi harus lebih dari jumlah pre order masuk [jumlah pre order masuk: $tot]", "token" => $this->security->get_csrf_hash()));
							exit;
						}
					}

					$this->db->where("id", $_POST["id"][$i]);
					$this->db->update("produk_variasi", $data);
				} else {
					$this->db->insert("produk_variasi", $data);
				}
			}
			echo json_encode(array("success" => true, "msg" => "berhasil", "token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array("success" => false, "msg" => "form not submitted!"));
		}
	}

	/*------------------------------------------------------------------------*/
	
	private function clean($string)
	{
		$string = str_replace(' ', '-', $string);
		$string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);

		return preg_replace('/-+/', '-', $string);
	}
}