<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Judul</th>
			<th scope="col">Link</th>
			<th scope="col">Isi</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$rows = $this->db->get("@page");
		$rows = $rows->num_rows();
		
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("@page");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$r->nama?></td>
				<td><?=$r->slug?></td>
				<td><?=$this->setting->potong($r->konten,40,"...")?></td>
				<td>
				<button type="button" onclick="edit(<?=$r->id?>)" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i> Edit</button>
				<button type="button" onclick="hapus(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> Hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadHalaman");?>
</div>