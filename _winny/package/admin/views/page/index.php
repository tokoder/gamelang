<a href="javascript:tambah()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Halaman</a>
<h4 class="page-title">Halaman Statis</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
    if ($('#konten').length > 0) {
        init_tinymce('#konten', 500);
    }

	$(function(){
		loadHalaman(1);
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
		
		$("#rekeningform").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var datar = $("#rekeningform").serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?=site_url("api/v1/page/update")?>",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							loadHalaman(1);
							$("#modal").modal("hide");
							swal.fire("Berhasil","data halaman sudah disimpan","success");
						}else{
							swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
						}
					});
				}
			});
		});
	});
	
	function loadHalaman(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/page?load=hal&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function edit(id){
		$.post("<?=site_url('api/v1/page')?>",{"formid":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			$("#theid").val(id);
			$("#nama").val(data.data.nama);
			//$("#konten").html(data.data.konten);
			tinymce.get('konten').setContent(data.data.konten);
			$("#modal").modal();
		});
	}
	function tambah(id){
		$("#theid").val(0);
		$("#nama").val("");
		//$("#konten").html(data.data.konten);
		tinymce.get('konten').setContent("");
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
				$.post("<?=site_url("api/v1/page/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadHalaman(1);
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
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title">Edit Halaman</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?=form_open('', 'id="rekeningform"');?>
						<input type="hidden" name="id" id="theid" value="" />
						<div class="form-group">
							<label>Judul/Nama</label>
							<input type="text" id="nama" name="nama" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Konten Halaman</label>
							<textarea class="form-control" id="konten" name="konten"></textarea>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>