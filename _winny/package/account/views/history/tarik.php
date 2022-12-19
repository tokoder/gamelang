<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

if ($saldo->num_rows() > 0) {
?>
	<div class="table-list">
		<?php
		foreach ($saldo->result() as $res) {
			$old = ($res->darike != 2) ? "[invoice]" : "[rekening]";
			switch ($res->darike) {
				case '1':
					$new = $this->sales_model->getTransaksi($res->sambung, "orderid");
					break;
				case '2':
					$new = $this->user_model->getSaldotarik($res->sambung, "idrek");
					$new = $this->setting->getRekening($new, "semua");
					$bank = $this->setting->getBank($new->idbank, "nama");
					$new = $bank . " a/n " . $new->atasnama . " (" . $new->norek . ")";
					break;
				case '3':
					$new = $this->setting->getBayar($res->sambung, "invoice");
					break;
				case '4':
					$new = $this->sales_model->getTransaksi($res->sambung, "orderid");
					break;
				default:
					$new = "";
					break;
			}
			$status = ($res->darike == 2) ? $this->user_model->getSaldotarik($res->sambung, "status") : 1;
			$status = ($status == 1) ? "<span class='text-success'>Berhasil</span>" : "<span class='text-danger'>Sedang Diproses</span>";
			$jumlah = $this->setting->formUang($res->jumlah);
			$jumlah = ($res->darike != 2 and $res->darike != 3) ? "<span class='text-success'>Rp " . $jumlah . "</span>" : "<span class='text-danger'>Rp " . $jumlah . "</span>";
		?>
			<div class="table-item">
				<div class="row">
					<div class="col-md-3">
						<p><?php echo $this->setting->ubahTgl("d M Y H:i", $res->tgl); ?></p>
					</div>
					<div class="col-md-3">
						<p><?php echo str_replace($old, $new, $this->user_model->getSaldodarike($res->darike, "keterangan")); ?></p>
					</div>
					<div class="col-md-2">
						<?php echo $status; ?>
					</div>
					<div class="col-md-2 font-bold text-dark">
						Rp &nbsp;<?php echo $jumlah; ?>
					</div>
					<div class="col-md-2 text-right">
						Rp <?php echo $this->setting->formUang($res->saldoakhir); ?>
					</div>
				</div>
			</div>
		<?php
		}
		?>
	</div>
<?php
	echo $this->setting->createPagination($rows, $page, $perpage, "historySaldo");
} else {
	echo "
        <i class='fas fa-exchange-alt fs-40 m-b-10 text-danger'></i><br/>
        <h5>BELUM ADA TRANSAKSI</h5>
    ";
}
?>