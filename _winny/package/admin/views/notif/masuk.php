<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		$blink = "";

		$this->db->select("MAX(id) AS id");
		if($_GET["load"] == "baca"){
			$blink = "blink";
			$this->db->where("baca",0);
		}
		$this->db->where("(isipesan LIKE '%$cari%' OR tgl LIKE '%$cari%') AND tujuan = 0");
		$this->db->group_by("dari");
		$dbs = $this->db->get("pesan");
		$idin = array(0);
		foreach($dbs->result() as $is){
			$idin[] = $is->id;
		}
		
		$this->db->select("id");
		$this->db->where_in("id",$idin);
		//$this->db->group_by("dari");
		$rows = $this->db->get("pesan");
		$rows = $rows->num_rows();
		
		$this->db->where_in("id",$idin);
		//$this->db->group_by("dari");
		$this->db->order_by("id DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("pesan");
			
		if($rows > 0){
			$no = 1;
			foreach($db->result() as $r){
	?>
		<div class="pesanmasuk" onclick="openPesan(<?=$r->dari?>)">
			<div class="nama">
				<i class="float-right"><small><?=$this->setting->ubahTgl("d/m/Y H:i",$r->tgl)?></small></i>
				<i class="fas fa-circle text-success <?=$blink?>"></i> &nbsp;<?=strtoupper($this->user_model->getProfil($r->dari,"nama","usrid"))?>
			</div>
			<div class="isipesan"><?=$this->setting->potong($this->setting->clean($r->isipesan),60,"..")?></div>
		</div>
	<?php	
				$no++;
			}
		}else{
			echo "<div class='well well-success text-center text-danger'>Belum ada pesan</div>";
		}
	?>

	<?=$this->setting->createPagination($rows,$page,$perpage,"loadSemua");?>