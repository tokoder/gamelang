<div class="text-center m-t-20 m-b-30">
	<h4><b>LAPORAN TRANSAKSI PENJUALAN</b></h4><br/>
	Periode: <?=$this->setting->ubahTgl("d/m/Y",$_POST["tglmulai"])?> sampai <?=$this->setting->ubahTgl("d/m/Y",$_POST["tglselesai"])?>
</div>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Tanggal</th>
			<th scope="col">ID Transaksi</th>
			<th scope="col">Nama</th>
			<th scope="col">Total</th>
			<th scope="col">Ongkir</th>
		</tr>
	<?php
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->order_by("selesai","ASC");
		$this->db->where("status = '3' AND selesai BETWEEN '".$_POST["tglmulai"]." 00:00:00' AND '".$_POST["tglselesai"]." 23:59:59'");
		$db = $this->db->get("sales");
			
		if($db->num_rows() > 0){
			$no = 1;
			$total = 0;
			$totalongkir = 0;
			foreach($db->result() as $r){
				$bayar = $this->setting->getBayar($r->idbayar,"semua");
				$total += $bayar->total-$bayar->kodebayar;
				$totalongkir += $r->ongkir;
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$this->setting->ubahTgl("d/m/Y H:i",$r->selesai)?></td>
				<td><?=$r->orderid?></td>
				<td><?=strtoupper(strtolower($this->user_model->getProfil($r->usrid,"nama","usrid")))?></td>
				<td class='text-right'><?=$this->setting->formUang($bayar->total-$bayar->kodebayar)?></td>
				<td class='text-right'><?=$this->setting->formUang($r->ongkir)?></td>
			</tr>
	<?php	
				$no++;
			}
			echo "
			<tr>
				<th class='text-right' colspan=4>TOTAL</th>
				<th class='text-right'>Rp. ".$this->setting->formUang($total)."</th>
				<th class='text-right'>Rp. ".$this->setting->formUang($totalongkir)."</th>
			</tr>
			";
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>
</div>