<h4 class="page-title">Pre Order Produk</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header row">
			<div class="tabs p-lr-15">
				<a href="javascript:loadPreorder(1)" class="tabs-item preorder active">
					<i class="fas fa-box"></i> Semua Pesanan
				</a>
				<a href="javascript:loadProduk(1)" class="tabs-item produk">
					<i class="fas fa-boxes"></i> Per Produk
				</a>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadPreorder(1);
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
	});
	
	function loadPreorder(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/preorder?load=preorder&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function loadProduk(page){
		$("#load").html('<i class="fas fa-spin fa-compact-disc"></i> Loading data...');
		$.post("<?=site_url("admin/preorder?load=produk&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function detailPesanan(id){
		$.post("<?=site_url("admin/preorder?load=preorder&page=1&idproduk=")?>"+id,{[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#loads").html(data.result);
			$("#modal").modal();
		});
	}
</script>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Daftar Pesanan</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="loads">
			</div>
		</div>
	</div>
</div>