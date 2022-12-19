<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">No</th>
			<th scope="col">Nama</th>
			<th scope="col">Username</th>
			<th scope="col">Level</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$this->db->where("id !=",$_SESSION["uid"]);
		$this->db->where("id >",1);
		$rows = $this->db->get("user_admin");
		$rows = $rows->num_rows();
		
		$this->db->order_by("id","DESC");
		$this->db->where("id !=",$_SESSION["uid"]);
		$this->db->where("id >",1);
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("user_admin");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$level = ($r->level == 1) ? "Admin" : "Owner";
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$r->nama?></td>
				<td><?=$r->username?></td>
				<td><?=$level?></td>
				<td>
					<button onclick="editUser(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-pencil-alt"></i> edit</button>
					<button onclick="hapusUser(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada user</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadUser");?>
</div>