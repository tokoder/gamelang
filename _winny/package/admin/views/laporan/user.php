<h4 class="page-title">Riwayat Transaksi Pengguna</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header">
			<div class="row" style="align-items:center;">
				<div class="col-md-6 m-b-10" style="font-size:120%;"><i class="fas fa-filter"></i> &nbsp;Periode Laporan</div>
				<div class="col-md-6 text-right">
					<!--<button onclick="saveDiv('load','Laporan Penjualan')" class="btn btn-warning"><i class="fas fa-file-pdf"></i> Download PDF</button>-->
					<button onclick="printDiv('load','Laporan Penjualan')" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
				</div>
			</div>
			<div class="row" style="align-items:center;">
				<div class="col-12 row m-t-10" style="align-items:center;">
					<div class="col-md-2 m-t-10">Pengguna</div>
					<div class="col-md-2 m-t-10">
                        <select id="level" class="form-control">
                            <option value='1'>User Normal</option>
                            <option value='2'>Reseller</option>
                            <option value='3'>Agen</option>
                            <option value='4'>Agen Premium</option>
                            <option value='5'>Distributor</option>
                        </select>
                    </div>
					<div class="col-md-8 m-t-10 p-lr-5">
                        <select id="usrid" class="form-control">
                            <option value='0'>== Pilih Level Terlebih Dahulu ==</option>
                        </select>
                    </div>
				</div>
            </div>
            <hr/>
			<div class="row" style="align-items:center;">
				<div class="col-12 col-md-4 row m-t-20" style="align-items:center;">
					<div class="col-4">Mulai</div>
					<input type="text" id="tglmulai" class="form-control datepicker col-8" value="<?=date("Y-m-d",strtotime("-30 day", strtotime(date("Y-m-d"))))?>" />
				</div>
				<div class="col-12 col-md-4 row m-t-20" style="align-items:center;">
					<div class="col-4">Selesai</div>
					<input type="text" id="tglselesai" class="form-control datepicker col-8" value="<?=date("Y-m-d")?>" />
				</div>
			</div>
		</div>
		<div class="card-body" id="load">
			<div class="m-tb-40 text-center text-danger">PILIH PENGGUNA UNTUK MENAMPILKAN LAPORAN</div>
		</div>
	</div>
</div>
<div id="editor"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script type="text/javascript">
	$(function(){
        //loadRiwayat();
        $("#usrid").load("<?=site_url("api/v1/user")?>?level=1");
        
        $("#level").change(function(){
            $("#usrid").load("<?=site_url("api/v1/user")?>?level="+$(this).val());
        });

		$(".datepicker").on("dp.change",function(){
            if($("#usrid").val()){
                loadRiwayat();
            }
		});
		$("#usrid").on("change",function(){
			loadRiwayat();
		});

		$(".datepicker").datetimepicker({
			format: "YYYY-MM-DD"
		});
		
		$(".tabs-item").on('click',function(){
			$(".tabs-item.active").removeClass("active");
			$(this).addClass("active");
		});
	});
	
	function loadRiwayat(){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/laporan/user?load=hal")?>",{"usrid":$("#usrid").val(),"tglmulai":$("#tglmulai").val(),"tglselesai":$("#tglselesai").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
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