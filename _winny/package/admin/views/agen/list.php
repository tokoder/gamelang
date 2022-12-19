<div class="table-responsive">
	<table class="table table-condensed table-hover">
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$where = "(username LIKE '%$cari%' OR tgl LIKE '%$cari%')";
		if($_GET["load"] == "distri"){
			$where .= " AND level = 5";
			$fungsi = "loadDistri";
			$head = "Distributor";
		}elseif($_GET["load"] == "reseller"){
			$where .= " AND level = 2";
			$fungsi = "loadReseller";
			$head = "Reseller";
		}elseif($_GET["load"] == "agen"){
			$where .= " AND level = 3";
			$fungsi = "loadAgen";
			$head = "Agen";
		}elseif($_GET["load"] == "agensp"){
			$where .= " AND level = 4";
			$fungsi = "loadAgenSP";
			$head = "Agen Premium";
		}else{
			$where .= " AND level = 1";
			$fungsi = "loadUser";
			$head = "User";
		}
	?>
		<tr>
			<th scope="col">No</th>
			<th scope="col">Nama <?php if(isset($head)){ echo $head; }else{ echo "Agen"; } ?></th>
			<th scope="col">No HP</th>
			<th scope="col">Total Order</th>
			<th scope="col">Ubah Level</th>
		</tr>
	<?php
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
				<td><?=$no?></td>
				<td><?=$user->nama?></td>
				<td><?=$user->nohp?></td>
				<td>Rp. <?=$this->setting->formUang($total)?></td>
				<td>
					<?php if($_GET["load"] != "normal"){ ?>
					<button type="button" onclick="addNormal(<?=$r->id?>)" class="btn btn-xs btn-info"><i class="fas fa-random"></i> Normal</button>
					<?php }if($_GET["load"] != "reseller"){ ?>
					<button type="button" onclick="addReseller(<?=$r->id?>)" class="btn btn-xs btn-secondary"><i class="fas fa-random"></i> Reseller</button>
					<?php }if($_GET["load"] != "agen"){ ?>
					<button type="button" onclick="addAgen(<?=$r->id?>)" class="btn btn-xs btn-primary"><i class="fas fa-random"></i> Agen</button>
					<?php }if($_GET["load"] != "agensp"){ ?>
					<button type="button" onclick="addAgenSP(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-random"></i> Premium</button>
					<?php }if($_GET["load"] != "distri"){ ?>
					<button type="button" onclick="addDistri(<?=$r->id?>)" class="btn btn-xs btn-success"><i class="fas fa-random"></i> Distributor</button>
					<?php } ?>
				</td>
				<td>
					<button type="button" onclick="hapusUserdata(<?=$r->id?>)" class="btn btn-xs btn-danger"><i class="fas fa-times"></i> Hapus</button>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=5 class='text-center text-danger'>Belum ada ".ucwords($_GET["load"])."</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage,$fungsi);?>
</div>

<script type="text/javascript">
	function hapusUserdata(id){
		swal.fire({
			text: "user ini akan dihapus secara permanen, termasuk semua riwayat transaksi penjualannya",
			title: "Menghapus user?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/v1/agen/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadUser(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>