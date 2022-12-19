<h4 class="page-title">Variasi Produk</h4>

<div class="row m-b-60">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<a href="javascript:tambahWarna()" class="btn btn-success float-right"><i class="la la-plus"></i> Tambah</a>
					Variasi
				</div>
			</div>
			<div class="card-body" id="warna">
				<i class="fas fa-spin fa-spinner"></i> Loading data...
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					<a href="javascript:tambahSize()" class="btn btn-success float-right"><i class="la la-plus"></i> Tambah</a>
					Sub Variasi
				</div>
			</div>
			<div class="card-body" id="size">
				<i class="fas fa-spin fa-spinner"></i> Loading data...
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$("#warna").load("<?=site_url("admin/variasi?load=warna")?>");
		$("#size").load("<?=site_url("admin/variasi?load=size")?>");
		
		$("#simpan").on("submit",function(e){
			e.preventDefault();
			var btn = $("#submit").html();
			$("#submit").html("<i class='fas fa-spin fa-spinner'></i> Menyimpan...");
			$("#submit").prop("disabled",true);
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

			$.post("<?=site_url("admin/variasi/tambah")?>",datar,function(e){
				var data = eval("("+e+")");
				updateToken(data.token);
				$("#submit").html(btn);
				$("#submit").prop("disabled",false);
				$("#modal").modal("hide");
				
				if(data.success == true){
					swal.fire("Berhasil","berhasil menyimpan data","success").then((vl)=>{
						if($("#jenis").val() == "warna"){
							refreshWarna(1);
						}else{
							refreshSize(1);
						}
					});
				}else{
					swal.fire("Gagal","gagal menyimpan data, ulangi beberapa saat lagi","error");
				}
			});
		});
	});
	
	function refreshWarna(page){
		$("#warna").load("<?=site_url("admin/variasi?load=warna&page=")?>"+page);
	}
	function refreshSize(page){
		$("#size").load("<?=site_url("admin/variasi?load=size&page=")?>"+page);
	}
	
	function hapusSize(id){
		swal.fire({
			title: "Yakin menghapus?",
			text: "data yang sudah dihapus tidak akan bisa dikembalikan",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal",
			confirmButtonText: "Oke"
		}).then((val)=>{
			if(val.value == true){
				$.post("<?=site_url("admin/variasi/size")?>",{"theid":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil","data telah dihapus","success").then((val)=>{
							window.location.href="<?=site_url("admin/variasi")?>";
						});
					}else{
						swal.fire("Gagal!","gagal menghapus data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function hapusWarna(id){
		swal.fire({
			title: "Yakin menghapus?",
			text: "data yang sudah dihapus tidak akan bisa dikembalikan",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal",
			confirmButtonText: "Oke"
		}).then((val)=>{
			if(val.value == true){
				$.post("<?=site_url("admin/variasi/warna")?>",{"theid":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil","data telah dihapus","success").then((val)=>{
							window.location.href="<?=site_url("admin/variasi")?>";
						});
					}else{
						swal.fire("Gagal!","gagal menghapus data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
	
	function tambahWarna(){
		$(".modal-title").html("<i class='fas fa-tags'></i> Tambah Variasi");
		$("#id").val(0);
		$("#nama").val("");
		$("#jenis").val("warna");
		$("#modal").modal();
	}
	function tambahSize(){
		$(".modal-title").html("<i class='fas fa-tags'></i> Tambah Sub Variasi");
		$("#id").val(0);
		$("#nama").val("");
		$("#jenis").val("size");
		$("#modal").modal();
	}
	function editWarna(id,nama){
		$(".modal-title").html("<i class='fas fa-tags'></i> Edit Variasi");
		$("#id").val(id);
		$("#nama").val(nama);
		$("#jenis").val("warna");
		$("#modal").modal();
	}
	function editSize(id,nama){
		$(".modal-title").html("<i class='fas fa-tags'></i> Edit Sub Variasi");
		$("#id").val(id);
		$("#nama").val(nama);
		$("#jenis").val("size");
		$("#modal").modal();
	}
</script>

	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title"><i class="fas fa-plus"></i> Tambah Data</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
            	<?= form_open(site_url("admin/variasi/tambah"), 'id="simpan"'); ?>
					<input type="hidden" id="id" name="id" value="0" />
					<input type="hidden" id="jenis" name="jenis" value="" />
					<div class="modal-body">
						<div class="form-group">
							<label>Nama</label>
							<input type="text" class="form-control" id="nama" name="nama" required />
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="submit" class="btn btn-success">Simpan</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>