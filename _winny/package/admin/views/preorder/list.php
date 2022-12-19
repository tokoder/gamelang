<?php if($_GET["load"] == "preorder"){ ?>
<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Invoice</th>
			<th scope="col">Tanggal</th>
			<th scope="col">Pembeli</th>
			<th scope="col">Produk</th>
			<th scope="col">Jumlah</th>
			<th scope="col">Total DP</th>
			<th scope="col">Aksi</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		if(isset($_GET["idproduk"])){ $this->db->where("idproduk",$_GET["idproduk"]); }
		if(isset($_GET["po"])){ $this->db->where("status",$_GET["po"]); }
		$rows = $this->db->get("preorder");
		$rows = $rows->num_rows();
		
		if(isset($_GET["idproduk"])){ $this->db->where("idproduk",$_GET["idproduk"]); }
		if(isset($_GET["po"])){ $this->db->where("status",$_GET["po"]); }
		$this->db->order_by("status","ASC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("preorder");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				switch($r->status){
					case 0: $status = "<div class='badge badge-sm badge-danger'>belum dibayar</div>";
					break;
					case 1: $status = "<div class='badge badge-sm badge-success'>sudah dibayar</div>";
					break;
					default: $status = "";
					break;
				}
				if($r->bukti != ""){
					$status .= "<br/><a href='javascript:void(0)' onclick='bukti(\"".base_url("uploads/konfirmasi/".$r->bukti)."\")'>&raquo; Lihat Bukti Transfer</a>";
				}
	?>
			<tr>
				<td>#<?=$r->orderid."<br/>".$status?></td>
				<td><?=$this->setting->ubahTgl("d/m/Y H:i",$r->tgl)?></td>
				<td><?=$this->user_model->getUser($r->usrid,"username")?></td>
				<td><?=$this->setting->getProduk($r->idproduk,"nama")?></td>
				<td><?=$this->setting->formUang($r->jumlah)?></td>
				<td>Rp. <?=$this->setting->formUang($r->total)?></td>
				<td>
					<?php if($r->status == 0){ ?>
					<button type="button" onclick="konfirm(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-check-circle"></i> Verifikasi</button>
					<?php } ?>
				</td>
			</tr>
	<?php	
				$no++;
			}
		}else{
			echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada data</td></tr>";
		}
	?>
	</table>

	<?=$this->setting->createPagination($rows,$page,$perpage);?>
</div>
<script type="text/javascript">
	function konfirm(id){
		swal.fire({
			title: "Perhatian!",
			text: "pastikan uang sudah benar-benar masuk/ditranfer, lebih baik cek kembali mutasi.",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((val)=>{
			if(val.value){
				$.post("<?=site_url("api/v1/preorder/update")?>",{"id":id,"status":1,[$("#names").val()]:$("#tokens").val()},function(e){
					var data = eval("("+e+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Pesanan siap untuk segera dikirim","success");
						loadPreorder(1);
					}else{
						swal.fire("Gagal!","Terjadi kendala saat mengupdate data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	
	function bukti(url){
		$("#bukti").attr("src",url);
		$("#modalbukti").modal();
	}
</script>

<div class="modal fade" id="modalbukti" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<img id="bukti" src="<?=base_url('assets/img/no-image.png')?>" style='width:100%;' />
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="table-responsive">
	<table class="table table-condensed table-hover table-bordered">
		<tr>
			<th scope="col" rowspan=2>No</th>
			<th scope="col" rowspan=2>Nama Produk</th>
			<th scope="col" rowspan=2>Kuota PO<br/>[harga normal]</th>
			<th scope="col" colspan=2 class="text-center">Total PO</th>
			<th scope="col" rowspan=2>Aksi</th>
		</tr>
		<tr>
			<th scope="col">Belum Bayar</th>
			<th scope="col">Sudah Bayar</th>
		</tr>
	<?php
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) AND $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;
		
		$this->db->select("id");
		$this->db->where("preorder_id",1);
		$rows = $this->db->get("produk");
		$rows = $rows->num_rows();
		
		$this->db->where("preorder_id",1);
		$this->db->order_by("id","DESC");
		$this->db->limit($perpage,($page-1)*$perpage);
		$db = $this->db->get("produk");
			
		if($rows > 0){
			$no = 1;
			$total = 0;
			foreach($db->result() as $r){
				$this->db->where("idproduk",$r->id);
				$as = $this->db->get("produk_variasi");
				$kuota = 0;$kuotatotal = 0;
				foreach($as->result() as $rs){
					$kuotatotal += $rs->kuota * $r->harga;
					$kuota += $rs->kuota;
				}
				$this->db->where("idproduk",$r->id);
				$as = $this->db->get("preorder");
				$kuotas = 0;$kuotastotal = 0;
				$kuotab = 0;$kuotabtotal = 0;
				foreach($as->result() as $rs){
					if($rs->status == 0){
						$kuotabtotal += $rs->total;
						$kuotab += $rs->jumlah;
					}else{
						$kuotastotal += $rs->total;
						$kuotas += $rs->jumlah;
					}
				}
	?>
			<tr>
				<td><?=$no?></td>
				<td><?=$r->nama?></td>
				<td><?=$this->setting->formUang($kuota)."<br/>[".$this->setting->formUang($kuotatotal)."]"?></td>
				<td><?=$this->setting->formUang($kuotab)."<br/>[".$this->setting->formUang($kuotabtotal)."]"?></td>
				<td><?=$this->setting->formUang($kuotas)."<br/>[".$this->setting->formUang($kuotastotal)."]"?></td>
				<td>
					<button type="button" onclick="detailPesanan(<?=$r->id?>)" class="btn btn-xs btn-warning"><i class="fas fa-list"></i> Daftar Pesanan</button>
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

	<?=$this->setting->createPagination($rows,$page,$perpage);?>
</div>
<?php } ?>