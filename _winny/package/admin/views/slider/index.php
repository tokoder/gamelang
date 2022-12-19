<div style="">
	<a class="float-right btn btn-primary" href="<?=site_url("admin/slider/form")?>"><i class="fas fa-plus-circle"></i> Tambah Promo</a>
	<h4 class="page-title">Promo Slider</h4>
</div>
<div class="m-lr-0">
	<div class="card">
		<div class="card-body table-responsive">
			<table class="table mt-3">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Judul</th>
					<th scope="col">Tipe</th>
					<th scope="col">Status</th>
					<th scope="col">Masa Promo</th>
					<th scope="col">Aksi</th>
				</tr>
		<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
			$perpage = 10;

			$rows = $this->db->get("@promo");
			$rows = $rows->num_rows();

			$this->db->from("@promo");
			$this->db->order_by($orderby,"desc");
			$this->db->limit($perpage,($page-1)*$perpage);
			$pro = $this->db->get();
			
			if($rows > 0){
				$no = 1;
			foreach($pro->result() as $r){
				$mulai = $this->setting->ubahTgl("YmdHis",$r->tgl);
				$selesai = $this->setting->ubahTgl("YmdHis",$r->tgl_selesai);
				$tipe = ($r->jenis == 1) ? "<b class='text-success'>SLIDER</b>" : "<b class='text-danger'>IKLAN</b>";
				$status = ($r->status == 1 AND $mulai <= date("YmdHis") AND $selesai>= date("YmdHis")) ? "<span class='badge badge-success'>AKTIF</span>" : "<span class='badge badge-danger'>NONAKTIF</span>";
		?>
			<tr>
				<td class="text-center"><img style="max-height:70px;max-width:120px;" src="<?=base_url("uploads/promo/".$r->gambar)?>" /></td>
				<td><?=strtoupper(strtolower($r->caption))?></td>
				<td><?=$tipe?></td>
				<td><?=$status?></td>
				<td><b class="text-muted"><?="Mulai: </b>".$this->setting->ubahTgl("d/m/Y H:i",$r->tgl)."<br/><b class=\"text-muted\">Selesai:</b> ".$this->setting->ubahTgl("d/m/Y H:i",$r->tgl_selesai)?></td>
				<td>
					<a href="<?php echo site_url("admin/slider/form/".$r->id); ?>" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> Edit</a>
					<a href="javascript:hapusPromo(<?php echo $r->id; ?>)" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
				</td>
			</tr>
		<?php	
				$no++;
			}
			}else{
				echo "<tr><td colspan=5>Belum ada promo</td></tr>";
			}
		?>
		</table>

		<?=$this->setting->createPagination($rows,$page,$perpage);?>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		
	});
	
	function hapusPromo(pro){
		swal.fire({
			title: "Anda yakin menghapus?",
			text: "promo yang sudah dihapus tidak dapat dikembalikan",
			type: "error",
  			showCancelButton: true,
  			cancelButtonColor: '#d33',
			cancelButtonText: "Batal",
			confirmButtonText: "Tetap Hapus"
		}).then((result)=>{
			if(result.value){
				$.post("<?php echo site_url('admin/slider/hapus'); ?>",{"pro":pro,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Berhasil menghapus data","success").then((data) =>{
							location.reload();
						});
					}else{
						swal.fire("Gagal!","Gagal menghapus data, terjadi kesalahan sistem","error");
					}
				});
			}
		});
	}
	
	function refreshTabel(page){
		window.location.href = "<?php echo site_url('admin/slider?page='); ?>"+page;
	}
</script>