<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notif Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @category	Controllers
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

class Notif extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('sales_model');
	}
    
    /*------------------------------------------------------------------------*/
    
	public function masuk($usrid)
	{
		if (isset($usrid)) {
			$this->db->where("(dari = 0 AND tujuan = " . $usrid . ") OR (dari = " . $usrid . " AND tujuan = 0)");
			$this->db->limit(100);
			$db = $this->db->get("pesan");

			$this->db->where("dari", $usrid);
			$this->db->where("baca", 0);
			$this->db->update("pesan", array("baca" => 1));

			$currdate = false;
			if ($db->num_rows() > 0) {
				$noe = 1;
				foreach ($db->result() as $r) {
					$centang = ($r->baca == 0) ? "<i class='fas fa-check'></i>" : "<i class='fas fa-check-double'></i>";
					$centang = ($r->tujuan != 0) ? $centang : "";
					$loc = ($r->tujuan == 0) ? "left" : "right";
					$tgl = '<br/><small>' . $this->setting->ubahTgl("d/m H:i", $r->tgl) . ' &nbsp' . $centang . '</small>';

					if ($this->setting->ubahTgl("d-m-Y", $r->tgl) != $currdate) {
						echo '<div class="pesanwrap center">
								<div class="isipesan">' . $this->setting->ubahTgl("d M Y", $r->tgl) . '</div>
							</div>';
						$currdate = $this->setting->ubahTgl("d-m-Y", $r->tgl);
					}

					echo '<div class="pesanwrap ' . $loc . '">
							<div class="isipesan"><b>' . $this->setting->clean($r->isipesan) . "</b>" . $tgl . '</div>
						</div>';
					$noe++;
				}
			} else {
				echo '
					<div class="pesanwrap center">
						<div class="isipesan">belum ada pesan</div>
					</div>';
			}
		} else {
			echo '
				<div class="pesanwrap center">
					<div class="isipesan">belum ada pesan</div>
				</div>';
		}
	}

	/*------------------------------------------------------------------------*/
	
	public function kirim()
	{
		if (isset($_POST['isipesan'])) {
			$data = array(
				"isipesan"	=> $this->setting->clean($_POST["isipesan"]),
				"tujuan"	=> $_POST["tujuan"],
				"baca"		=> 0,
				"dari"		=> 0,
				"tgl"		=> date("Y-m-d H:i:s")
			);
			$this->db->insert("pesan", $data);

			echo json_encode(array(
				"success" => true, 
				"token" => $this->security->get_csrf_hash()));
		} else {
			echo json_encode(array(
				"success" => false, 
				"token" => $this->security->get_csrf_hash()));
		}
	}
}