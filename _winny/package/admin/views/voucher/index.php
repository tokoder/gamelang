<a href="javascript:tambahVoucher()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Voucher</a>
<h4 class="page-title">Voucher Promo</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header row align-items-center">
			<div class="card-title col-md-8">
				Daftar Voucher
			</div>
			<div class="col-md-4">
				<input type="text" class="form-control" placeholder="cari voucher" id="cari" />
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadVoucher(1);
		
		$("#voucherform").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var datar = $("#voucherform").serialize()
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?=site_url("api/v1/voucher/tambah")?>",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							loadVoucher(1);
							$("#modal").modal("hide");
							swal.fire("Berhasil","voucher sudah tersimpan","success");
						}else{
							swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
						}
					});
				}
			});
		});
		
		$(".dtp").datetimepicker({
			format: "YYYY-MM-DD",
			minDate: "<?=date("Y-m-d")?>"
		});
		
		$("#tipe").change(function(){
			if($(this).val() == 2){
				$(".maks").hide();
			}else{
				$(".maks").show();
			}
		});
	});
	
	function loadVoucher(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/voucher/list?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function edit(id){
		$.post("<?=site_url('admin/voucher/list')?>",{"formid":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			$("#voucherid").val(id);
			$("#nama").val(data.nama);
			$("#deskripsi").val(data.deskripsi);
			$("#kode").val(data.kode);
			$("#mulai").val(data.mulai);
			$("#selesai").val(data.selesai);
			$("#potongan").val(data.potongan);
			$("#potonganmin").val(data.potonganmin);
			$("#potonganmaks").val(data.potonganmaks);
			$("#peruser").val(data.peruser);
			
			$("#jenis option").each(function(){
				if($(this).val() == data.jenis){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			$("#tipe option").each(function(){
				if($(this).val() == data.tipe){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			$("#produk option").each(function(){
				if($(this).val() == data.idproduk){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			
			$("#modal").modal();
		});
	}
	function tambahVoucher(){
		$('#voucherform')[0].reset();
		
		$("#modal").modal();
	}
	function hapus(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/v1/voucher/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadVoucher(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan Voucher</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
            	<?= form_open('', 'id="voucherform"'); ?>
					<input type="hidden" name="id" id="voucherid" value="0" />
					<div class="form-group">
						<label>Nama Voucher</label>
						<input type="text" id="nama" name="nama" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Deskripsi</label>
						<input type="text" id="deskripsi" name="deskripsi" class="form-control" required />
					</div>
					<div class="form-group">
						<label>Kode Voucher</label>
						<input type="text" id="kode" name="kode" class="form-control col-md-6 kodevoucher text-danger" required />
					</div>
                    <div class="form-group row m-lr-0">
                        <label class="col-12">Masa Berlaku</label>
						<div class="col-md-6 p-lr-5">
							<input type="text" id="mulai" name="mulai" class="form-control m-b-10 dtp" placeholder="tahun-bulan-tanggal" value="" required />
						</div>
						<div class="col-md-6 p-lr-5">
							<input type="text" id="selesai" name="selesai" class="form-control m-b-10 dtp" placeholder="tahun-bulan-tanggal" value="" required />
						</div>
                    </div>
					<div class="form-group">
						<label>Jenis Voucher</label>
						<select id="jenis" name="jenis" class="form-control" required >
							<option value="1">Potongan Harga</option>
							<option value="2">Potongan Ongkos Kirim</option>
						</select>
					</div>
					<div class="form-group"> 
						<label>Tipe Voucher</label>
						<select id="tipe" name="tipe" class="form-control" required >
							<option value="1">Persen (%)</option>
							<option value="2">Nilai Rupiah (Rp)</option>
						</select>
					</div>
					<?php
					/*
					<div class="form-group">
						<label>Produk Khusus</label>
						<select id="produk" name="idproduk" class="form-control" required >
							<option value="0">Semua Produk</option>
							<?php
								$this->db->where("status",1);
								$this->db->where("preorder_id",0);
								$this->db->order_by("nama","ASC");
								$dbs = $this->db->get("produk");
								foreach($dbs->result() as $rs){
									echo "<option value='".$rs->id."'>".$rs->nama." - Rp.".$this->setting->formUang($rs->harga)."</option>";
								}
							?>
						</select>
					</div>
					*/
					?>
					<div class="form-group maks">
						<label>Minimal Total Order</label>
						<input type="number" id="potonganmin" name="potonganmin" class="form-control col-md-8"  />
					</div>
					<div class="form-group">
						<label>Nilai Diskon/Potongan Harga</label>
						<input type="number" id="potongan" name="potongan" class="form-control col-md-6" required />
					</div>
					<div class="form-group maks">
						<label>Potongan Maksimal</label>
						<input type="number" id="potonganmaks" name="potonganmaks" class="form-control col-md-8"  />
					</div>
					<div class="form-group">
						<label>Penggunaan Maksimal Per User</label>
						<input type="number" id="peruser" name="peruser" value="1" min="1" class="form-control col-md-2" required />
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>