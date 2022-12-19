<a href="javascript:tambahAgen()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Agen</a>
<h4 class="page-title">Agen & Reseller</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header row">
			<div class="tabs col-md-8">
				<a href="javascript:void(0)" onclick="loadReseller(1)" class="tabs-item reseller active" data-level="reseller">
					Reseller
				</a>
				<a href="javascript:void(0)" onclick="loadAgen(1)" class="tabs-item agen" data-level="agen">
					Agen
				</a>
				<a href="javascript:void(0)" onclick="loadAgenSP(1)" class="tabs-item agen" data-level="agensp">
					Agen Premium
				</a>
				<a href="javascript:void(0)" onclick="loadDistri(1)" class="tabs-item distri" data-level="distri">
					Distributor
				</a>
			</div>
			<div class="col-md-4 row m-lr-0">
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
		loadReseller(1);
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
	});
	
	function loadReseller(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/agen?load=reseller&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function loadAgen(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/agen?load=agen&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function loadAgenSP(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/agen?load=agensp&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function loadDistri(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/agen?load=distri&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function loadAgenForm(page){
		$("#datauser").html('<div class="w-full text-center"><i class="fas fa-spin fa-spinner"></i> Loading data...</div>');
		$.post("<?=site_url("admin/agen/tambah")?>",{"cari":$("#caridata").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#datauser").html(data.result);
		});
	}
	function cariData(){
		if($(".tabs-item.active").data("level") == "distri"){
			loadDistri(1);
		}else if($(".tabs-item.active").data("level") == "reseller"){
			loadReseller(1);
		}else{
			loadAgen(1);
		}
	}
	function tambahAgen(){
		$("#modal").modal();
		loadAgenForm(1);
	}
	
	function addDistri(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus distributor",
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
						$(".distri").trigger("click");
						//loadDistri(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi distributor","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addAgenSP(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus agen premium",
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
						$(".agensp").trigger("click");
						//loadAgen(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi agen premium","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addAgen(id){
		swal.fire({
			text: "user ini akan mendapatkan harga khusus agen",
			title: "Menambahkan ke Agen?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":3,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						$(".agen").trigger("click");
						//loadAgen(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi agen","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
	function addNormal(id){
		swal.fire({
			text: "user ini akan mendapatkan harga normal",
			title: "Menambahkan ke Normal User?",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("admin/agen/tambah")?>",{"id":id,"level":1,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						$(".tabs-item.active").trigger("click");
						//loadReseller(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi normal user","success");
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
						$(".reseller").trigger("click");
						//loadReseller(1);
						$("#modal").modal("hide");
						swal.fire("Berhasil","user telah menjadi reseller","success");
					}else{
						swal.fire("Gagal!","gagal mengubah data user, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>

	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
			<div class="modal-content" style="min-height:90vh;">
				<div class="modal-header">
					<input type="text" onkeyup="loadAgenForm(1)" id="caridata" placeholder="cari user" class="form-control col-md-6 col-8" />
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="datauser">
					<div class="p-tb-10 p-lr-20"><i class="fas fa-spin fa-spinner"></i> Loading data...</div>
				</div>
			</div>
		</div>
	</div>