<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Nama</th>
			<th scope="col">Kode Voucher</th>
			<th scope="col">Jenis Potongan</th>
			<th scope="col">Nilai</th>
			<th scope="col">Masa Berlaku</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$where = "nama LIKE '%$cari%' OR deskripsi LIKE '%$cari%' OR kode LIKE '%$cari%' OR potongan LIKE '%$cari%'";
		$this->db->select("id");
		$this->db->where($where);
		$rows = $this->db->get("sales_voucher");
		$rows = $rows->num_rows();
		
		$this->db->where($where);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("sales_voucher");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$pot = $this->setting->formUang($r->potongan);
				$potongan = $r->tipe == 2 ? "Rp ".$pot : $pot."%";
				$jenis = $r->jenis == 1 ? "Harga" : "Ongkir";
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$r->nama?></td>
				<td><?=strtoupper(strtolower($r->kode))?></td>
				<td><?=$jenis?></td>
				<td><?=$potongan?></td>
				<td>
					<b>mulai:</b> <?=$this->setting->ubahTgl("d M Y",$r->mulai)?><br/>
					<b>selesai:</b> <?=$this->setting->ubahTgl("d M Y",$r->selesai)?>
				</td>
				<td>
					<button onclick="edit(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> edit</button>
					<?php if($_GET["load"] != "mutasi") : ?>
					<button onclick="hapus(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
					<?php endif; ?>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada voucher</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadVoucher");?>
</div>