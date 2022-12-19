<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Tanggal</th>
			<th scope="col">No Transaksi</th>
			<th scope="col">Nama Pembeli</th>
			<th scope="col">Total</th>
			<th scope="col">Total Ongkir</th>
			<th scope="col">Kurir</th>
			<th scope="col">Aksi</th>
		</tr>
		<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
			$perpage = 10;

			$where = "orderid LIKE '%$cari%' OR total LIKE '%$cari%' OR kodebayar LIKE '%$cari%'";
			$this->db->like("orderid",$cari);
			$this->db->where("status",3);
			$rows = $this->db->get("sales");
			$rows = $rows->num_rows();

			$this->db->like("orderid",$cari);
			$this->db->where("status",3);
			$this->db->order_by("id","DESC");
			$this->db->limit($perpage,($page-1)*$perpage);
			$db = $this->db->get("sales");
			
			if($rows > 0){
				$no = 1;
				foreach($db->result() as $r){
					$kurir = strtoupper($r->kurir." ".$r->paket);
					$this->db->where("idtransaksi",$r->id);
					$db = $this->db->get("sales_produk");
					$total = $r->ongkir;
					foreach($db->result() as $rs){
						$total += $rs->harga * $rs->jumlah;
					}
		?>
			<tr>
				<td class="text-center"><i class="fas fa-check-circle text-success"></i> &nbsp; <?=$this->setting->ubahTgl("d M Y H:i",$r->tgl);?></td>
				<td><?=$r->orderid?></td>
				<td><?=$this->user_model->getProfil($r->usrid,"nama","usrid")?></td>
				<td><?=$this->setting->formUang($total)?></td>
				<td><?=$this->setting->formUang($r->ongkir)?></td>
				<td><?=$kurir?></td>
				<td style="min-width:160px;">
					<a href="javascript:void(0)" onclick="detail(<?=$r->id?>)" class="btn btn-primary btn-xs"><i class="fas fa-list"></i> Detail</a>
					<?php if($r->kurir != "bayar" AND $r->kurir != "toko" AND $r->kurir != "cod"){ ?>
					<a href="javascript:lacakPaket('<?=$r->orderid?>')" class="btn btn-secondary btn-xs"><i class="fas fa-pallet"></i> Lacak</a>
					<?php } ?>
				</td>
			</tr>
		<?php	
					$no++;
				}
			}else{
				echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada pesanan</td></tr>";
			}
		?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadSelesai");?>
</div>