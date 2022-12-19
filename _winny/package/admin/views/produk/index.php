<a href="javascript:void(0)" onclick="importProduk()" class="btn btn-primary float-right"><i class="fas fa-download"></i> Impor Excel</a>
<a href="<?=site_url("admin/produk/form")?>" class="btn btn-success float-right m-r-10"><i class="fas fa-plus-circle"></i> Produk Baru</a>
<h4 class="page-title">Daftar Produk</h4>
<div class="card">
	<div class="card-header row">
		<div class="col-md-4">
			<input type="text" class="form-control" placeholder="cari produk" id="cari" />
		</div>
	</div>
	<div class="card-body table-responsive">
		<i class="la la-spin la-spinner"></i> Loading data...
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$.post("<?=site_url("admin/produk?load=true")?>&page=1",{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$(".card-body").html(data.result);
		});

		$("#impor").on("submit",function(e){
			e.preventDefault();

			var formData = new FormData();
			$(".progress").show();
			$(this).hide();
			formData.append("fileupload", $("#file").get(0).files[0]);
			formData.append($("#names").val(), $("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/v1/produk/import"); ?>',
                type       : 'POST',
                contentType: false,
                cache      : false,
                processData: false,
                data       : formData,
                xhr        : function ()
                {
                    var jqXHR = null;
                    if ( window.ActiveXObject ){
                        jqXHR = new window.ActiveXObject( "Microsoft.XMLHTTP" );
                    }else{
                        jqXHR = new window.XMLHttpRequest();
                    }
                    jqXHR.upload.addEventListener( "progress", function ( evt ){
                        if ( evt.lengthComputable ){
                            var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
                            $(".progress .progress-bar").css("width", percentComplete+"%");
                            $(".progress .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$("#impor").show();
					$(".progress").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#modalimpor").modal("hide");
						swal.fire("Berhasil","Data produk telah berhasil di impor","success").then(res=>{
							refreshTabel(1);
						});
					}else{
						swal.fire("Gagal Impor","Terjadi kesalahan saat server memproses file <br/><i class='text-danger'>"+res.msg+"</i>","error");
					}
                }
            } );
		});

		$("#cari").change(function(){
			$(".card-body").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
			$.post("<?=site_url("admin/produk")?>?page=1&load=true",{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
				var data = eval("("+msg+")");
				updateToken(data.token);
				$(".card-body").html(data.result);
			});
		});
	});
	
	function refreshTabel(page){
		$(".card-body").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/produk?load=true")?>&page="+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$(".card-body").html(data.result);
		});
	}

	function importProduk(){
		$("#modalimpor").modal();
	}
	
	function hapus(id){
		swal.fire({
			title: "Yakin menghapus?",
			text: "data yang sudah dihapus tidak akan bisa dikembalikan",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal",
			confirmButtonText: "Oke"
		}).then((val)=>{
			if(val.value == true){
				$.post("<?=site_url("api/v1/produk/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil","data telah dihapus","success").then((val)=>{
							window.location.href="<?=site_url("admin/produk")?>";
						});
					}else{
						swal.fire("Gagal!","gagal menghapus data, cobalah beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>


<div class="modal fade" id="modalimpor" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Impor Produk</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					Sebelum mengupload, silahkan ikuti format data untuk impor sesuai template yang telah disediakan.<br/>
					<a href="<?=base_url("import/Template_Import.xlsx")?>" class="btn btn-link"><i class="fas fa-file-download"></i> &nbsp;download template impor</a>
				</div>
				<?= form_open('', 'id="impor"'); ?>
					<div class="form-group">
						<label>File Excel (.xls / .xlsx / .csv)</label>
						<input type="file" id="file" name="file" class="form-control" required />
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Impor</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
				<div class="progress" style="display:none;">
					<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
</div>