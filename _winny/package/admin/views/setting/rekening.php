<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Bank</th>
			<th scope="col">No Rekening</th>
			<th scope="col">Atasnama</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$this->db->where("usrid",0);
		$rows = $this->db->get("user_rekening");
		$rows = $rows->num_rows();
		
		$this->db->where("usrid",0);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("user_rekening");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
	?>
			<tr>
				<td><?=$no?></td>
				<td>
					<?=$this->setting->getBank($r->idbank,"nama")?><br/>
					<?=$r->kcp?>
				</td>
				<td><?=$r->norek?></td>
				<td><?=$r->atasnama?></td>
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
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada rekening</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage);?>
</div>