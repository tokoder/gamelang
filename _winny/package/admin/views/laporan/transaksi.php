<h4 class="page-title">Riwayat Transaksi Penjualan</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header">
			<div class="row" style="align-items:center;">
				<div class="col-md-6 m-b-10" style="font-size:120%;"><i class="fas fa-filter"></i> &nbsp;Periode Laporan</div>
				<div class="col-md-6 text-right">
					<!--<button onclick="saveDiv('load','Laporan Penjualan')" class="btn btn-warning"><i class="fas fa-file-pdf"></i> Download PDF</button>-->
					<button onclick="printDiv('load','Laporan Penjualan')" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
				</div>
				<div class="col-12 col-md-4 row m-t-20" style="align-items:center;">
					<div class="col-4 text-right">Mulai</div>
					<input type="text" id="tglmulai" class="form-control datepicker col-8" value="<?=date("Y-m-d",strtotime("-30 day", strtotime(date("Y-m-d"))))?>" />
				</div>
				<div class="col-12 col-md-4 row m-t-20" style="align-items:center;">
					<div class="col-4 text-right">Selesai</div>
					<input type="text" id="tglselesai" class="form-control datepicker col-8" value="<?=date("Y-m-d")?>" />
				</div>
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>
<div id="editor"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script type="text/javascript">
	$(function(){
		loadRiwayat();

		$(".datepicker").on("dp.change",function(){
			loadRiwayat();
		});

		$(".datepicker").datetimepicker({
			format: "YYYY-MM-DD"
		});
		
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
					$.post("<?=site_url("api/v1/order/update")?>",datar,function(msg){
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
	
	function loadRiwayat(){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/laporan/transaksi?load=hal")?>",{"tglmulai":$("#tglmulai").val(),"tglselesai":$("#tglselesai").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}

	var doc = new jsPDF();
	var specialElementHandlers = {
            '#editor': function (element, renderer) {
            return true;
        }
    };
	function saveDiv(divId, title) {
		doc.fromHTML(
			`<html><head><title>${title}</title>`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">`+
			`<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">`+
			`</head><body>` + 
			$("#"+divId).html() + 
			`</body></html>`, 5, 5, {
            'width': 170,
                'elementHandlers': specialElementHandlers
        });
		doc.save('div.pdf');
	}
	function printDiv(divId,title) {

		let mywindow = window.open('', 'PRINT', 'height=650,width=900,top=100,left=150');

		mywindow.document.write(`<html><head><title>${title}</title>`);
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">');
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/util.css">');
		mywindow.document.write('<link rel="stylesheet" href="<?=base_url()?>/assets/css/minmin.css?v=<?=time()?>">');
		mywindow.document.write('</head><body>');
		mywindow.document.write($("#"+divId).html());
		mywindow.document.write('</body></html>');

		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10*/

		mywindow.print();
		setTimeout(function(){mywindow.close() },1000);

		return true;
	}
</script>

	<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title">Edit Rekening</h6>
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