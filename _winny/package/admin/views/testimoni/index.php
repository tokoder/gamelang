<a href="javascript:tambahSB()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Testimoni</a>
<h4 class="page-title">Testimoni Pembeli</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
			<div class="card-title">
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadTesti(1);
		
		$("#sbforms").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var formData = new FormData();
					$(".progress").show();
					$("#sbforms").hide();
					formData.append("foto", $("#imgInp").get(0).files[0]);
					formData.append($("#names").val(), $("#tokens").val());
					formData.append("id", $("#id").val());
					formData.append("nama", $("#nama").val());
					formData.append("jabatan", $("#jabatan").val());
					formData.append("komentar", $("#komentar").val());
					$.ajax({
						url        : '<?php echo site_url("api/v1/testimoni/tambah"); ?>',
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
									$(".progress-bar").css("width", percentComplete+"%");
									$(".progress-bar").attr("aria-valuenow", percentComplete);
								}
							}, false );
							return jqXHR;
						},
						success    : function ( data )
						{
							$("#sbforms").show("slow");
							$(".progress").hide();
							var res = eval("("+data+")");
							updateToken(res.token);
							$("#modal").modal("hide");
							swal.fire("Berhasil","Berhasil menyimpan testimoni","success");
							loadTesti(1);
						}
					});
				}
			});
		});
		
        $("#imgInp").change(function() {
            if($(this).val() != ""){
                readURL(this);
                $("#blah").show();
                $(".delete").show();
                $(".text").hide();
            }else{
                $("#blah").hide();
                $(".delete").hide();
                $(".text").show();
            }
        });
	});

	// FOTO UPLOAD
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    function selectIMG(){
        $("#imgInp").trigger("click");
    }
    function clearIMG(){
        $("#imgInp").val(null).trigger("change");
    }

	function loadTesti(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("admin/testimoni/list?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function edit(id){
		$.post("<?=site_url('admin/testimoni/list')?>",{"formid":id,[$("#names").val()]:$("#tokens").val()},function(ev){
			var data = eval("("+ev+")");
			updateToken(data.token);
			$("#id").val(id);
			$("#nama").val(data.nama);
			$("#jabatan").val(data.jabatan);
			$("#komentar").val(data.komentar);
			$("#blah").attr("src","<?=base_url()?>/uploads/"+data.foto);
			$("#blah").show();
			$(".delete").show();
			$(".text").hide()
			
			$("#modal").modal();
		});
	}
	function tambahSB(){
		//$('#sbforms')[0].reset();
		$("#id").val(0);
		$("#nama").val("");
		$("#jabatan").val("");
		$("#komentar").val("");
		$("#imgInp").val("");
		$("#blah").hide();
        $(".delete").hide();
        $(".text").show();
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
				$.post("<?=site_url("api/v1/testimoni/hapus")?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						loadTesti(1);
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
				<h6 class="modal-title">Pengaturan testimoni</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?= form_open('', 'id="sbforms"'); ?>
					<input type="hidden" name="id" id="id" value="0" />
                    <div class="form-group m-b-12">
                        <label>Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="" />
                    </div>
                    <div class="form-group m-b-12">
                        <label>Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" class="form-control" value="" placeholder="cth: Entrepreneur, Pembeli, dll"/>
                    </div>
                    <div class="form-group m-b-12">
                        <label>Komentar</label>
                        <textarea class="form-control" id="komentar" name="komentar"></textarea>
                    </div>
					<div class="form-group">
						<div id="inputfile">
							<input type='file' accept="image/*" name="icon" id="imgInp" />
							<div class="imgInpPreview pointer">
								<div class="text" onclick="selectIMG()">Pilih foto</div>
								<img id="blah" class="imgpreview" src="#" alt="gambar" />
								<div class="delete">
									<a href="javascript:void(0)" onclick="clearIMG()"><i class="la la-times"></i> ganti foto</a>
								</div>
							</div>
						</div>
                    </div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
				<div class="progress" style="display:none;">
					<div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
					<div class="text-center m-t-12">menyimpan testimoni</div>
				</div>
			</div>
		</div>
	</div>
</div>