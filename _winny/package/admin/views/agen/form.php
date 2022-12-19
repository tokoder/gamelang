<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Nama User</th>
			<th scope="col">No HP</th>
			<th scope="col">Total Order</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$where = "(username LIKE '%$cari%' OR tgl LIKE '%$cari%') AND level = 1";
		$this->db->select("id");
		$this->db->where($where);
		$rows = $this->db->get("user");
		$rows = $rows->num_rows();
		
		$this->db->where($where);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("user");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$user = $this->user_model->getProfil($r->id,"semua","usrid");
				$this->db->select("SUM(total) AS total,SUM(kodebayar) AS kodebayar");
				$this->db->where("usrid",$r->id);
				$this->db->where("status",1);
				$dbs = $this->db->get("invoice");
				foreach($dbs->result() as $res){
					$total = $res->total - $res->kodebayar;
				}
	?>
			<tr>
				<td><?=$r->username?></td>
				<td><?=$user->nohp?></td>
				<td>Rp. <?=$this->setting->formUang($total)?></td>
				<td>
					<button type="button" onclick="addReseller(<?=$r->id?>)" class="btn btn-xs btn-secondary"><i class="fas fa-plus"></i> Reseller</button>
					<button type="button" onclick="addAgen(<?=$r->id?>)" class="btn btn-xs btn-primary"><i class="fas fa-plus"></i> Agen</button>
					<button type="button" onclick="addAgenSP(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-plus"></i> Agen Premium</button>
					<button type="button" onclick="addDistri(<?=$r->id?>)" class="btn btn-xs btn-success"><i class="fas fa-plus"></i> Distributor</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>User tidak ditemukan</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadAgenForm");?>
</div>