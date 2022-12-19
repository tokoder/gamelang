<h4 class="page-title">User Manager</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header row">
			<div class="col-md-4 row m-lr-0 flex-end">
				<div class="col-10 p-lr-0"><input type="text" class="form-control" onchange="cariData()" placeholder="cari user" id="cari" /></div>
				<div class="col-2 p-lr-0"><button class="btn btn-sm btn-secondary w-full" onclick="cariData()"><i class="fas fa-search"></i></button></div>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadUser(1);
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
	});
	
	function loadUser(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/agen?load=normal&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function cariData(){
		loadUser(1);
	}
	
	function addAgen(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus agen/distributor",
			title: "Menambahkan ke Agen/Distributor?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":3,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadUser(1)
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi agen/distributor","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addReseller(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus reseller",
			title: "Menambahkan ke Reseller?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":2,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadUser(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi reseller","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addDistri(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus Distributor",
			title: "Menambahkan ke Distributor?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":5,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadUser(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi Distributor","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addAgenSP(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus Agen Premium",
			title: "Menambahkan ke Agen Premium?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":4,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadUser(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi Agen Premium","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
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