<?php
	$set = $this->setting->globalset("semua");
?>
<?=form_open('', 'id="pengaturan"');?>
	<div class="row">
		<div class="col-md-6 m-b-20">
			<div class="form-group">
				<label>Nama Toko</label>
				<input type="text" name="nama" class="form-control" value="<?=$set->nama?>" />
			</div>
			<div class="form-group">
				<label>Slogan</label>
				<input type="text" name="slogan" class="form-control" value="<?=$set->slogan?>" />
			</div>
			<div class="form-group">
				<label>Kota (Pengiriman)</label>
				<select class="form-control" name="kota">
					<?php
						$idkab = $this->rajaongkir->getCities()['rajaongkir']['results'];
						foreach($idkab as $r){
							$select = ($r['city_id'] == $set->kota) ? "selected" : "";
							echo "<option value='".$r['city_id']."' ".$select.">".$r['city_name']."</option>";
						}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>No Telepon</label>
				<input type="text" name="notelp" class="form-control col-6" value="<?=$set->notelp?>" />
			</div>
			<div class="form-group">
				<label>Jam Kerja</label>
				<input type="text" name="jamkerja" class="form-control col-6" value="<?=$set->jamkerja?>" />
			</div>
			<div class="form-group">
				<label>Whatsapp</label>
				<input type="text" name="wasap" class="form-control col-6" value="<?=$set->wasap?>" />
			</div>
			<div class="form-group">
				<label>Line ID</label>
				<input type="text" name="lineid" class="form-control col-8" value="<?=$set->lineid?>" />
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" class="form-control" value="<?=$set->email?>" />
			</div>
			<div class="form-group">
				<label>Instagram</label>
				<input type="text" name="instagram" class="form-control" value="<?=$set->instagram?>" />
			</div>
			<div class="form-group">
				<label>Facebook</label>
				<input type="text" name="facebook" class="form-control" value="<?=$set->facebook?>" />
			</div>
			<div class="form-group">
				<label>Alamat Lengkap</label>
				<textarea name="alamat" class="form-control" rows=4><?=$set->alamat?></textarea>
			</div>
			<div class="form-group m-t-20">
				<label><b>PENGATURAN WARNA APLIKASI</b></label>
			</div>
			<div class="row m-lr-0">
				<div class="form-group col-6">
					<label>Warna Utama (HEX)</label>
					<input type="text" name="color1" class="form-control" value="<?=$set->color1?>" />
				</div>
				<div class="form-group col-6">
					<label>Warna Utama (RGB)</label>
					<input type="text" name="color1rgba" class="form-control" value="<?=$set->color1rgba?>" />
				</div>
			</div>
			<div class="row m-lr-0">
				<div class="form-group col-6">
					<label>Warna Sekunder (HEX)</label>
					<input type="text" name="color2" class="form-control" value="<?=$set->color2?>" />
				</div>
				<div class="form-group col-6">
					<label>Warna Sekunder (RGB)</label>
					<input type="text" name="color2rgba" class="form-control" value="<?=$set->color2rgba?>" />
				</div>
			</div>
		</div>
		<div class="col-md-6 m-b-20">
			<div class="logoset">
				<div class="logo">
					<input type="file" name="logo" id="logoUpload" style="display:none;" accept="image/x-png,image/gif,image/jpeg" ></input>
					<div class="title">Logo Utama</div>
					<img id="logo" src="<?=base_url($this->setting->globalset("logo"))?>" />
					<button type="button" class="btn btn-secondary btn-block logouploadbtn" onclick="$('#logoUpload').trigger('click')"><i class="fas fa-sync"></i> Ganti Logo Utama</button>
					<div class="progress progreslogo" style="display:none;">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
			<div class="logoset">
				<div class="favicon">
					<input type="file" name="logo" id="faviconUpload" style="display:none;" accept="image/x-png,image/gif,image/jpeg" ></input>
					<div class="title">Logo Favicon</div>
					<img id="favicon" src="<?=base_url($this->setting->globalset("favicon"))?>" />
					<button type="button" class="btn btn-secondary btn-block faviconuploadbtn" onclick="$('#faviconUpload').trigger('click')"><i class="fas fa-sync"></i> Ganti Logo Favicon</button>
					<div class="progress progresfavicon" style="display:none;">
						<div class="progress-bar progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 m-b-20">
			<div class="form-group">
				<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
				<button type="reset" class="btn btn-warning"><i class="fas fa-sync-alt"></i> Reset</button>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(function(){
		$("#pengaturan").on("submit",function(e){
			e.preventDefault();
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
			$.post("<?=site_url("api/v1/setting/save")?>",datar,function(msg){
				var data = eval("("+msg+")");
				updateToken(data.token);
				if(data.success == true){
					swal.fire("Berhasil","berhasil menyimpan pengaturan umum","success").then((val)=>{
						loadSettingUmum();
					});
				}else{
					swal.fire("Gagal","gagal menyimpan pengaturan","error");
				}
			});
		});

		$("#faviconUpload").change(function(){
			var formData = new FormData();
			$(".progresfavicon").show();
			$(".faviconuploadbtn").hide();
			formData.append("logo", $(this).get(0).files[0]);
			formData.append($("#names").val(),$("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/v1/upload/logo/2"); ?>',
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
                            $(".progresfavicon .progress-bar").css("width", percentComplete+"%");
                            $(".progresfavicon .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$(".faviconuploadbtn").show("slow");
					$(".progresfavicon").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#favicon").attr("src","<?=base_url('uploads/logo/')?>"+res.filename);
					}
                }
            } );
		});

		$("#logoUpload").change(function(){
			var formData = new FormData();
			$(".progreslogo").show();
			$(".logouploadbtn").hide();
			formData.append("logo", $(this).get(0).files[0]);
			formData.append($("#names").val(),$("#tokens").val());
			$.ajax( {
                url        : '<?php echo site_url("api/v1/upload/logo/1"); ?>',
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
                            $(".progreslogo .progress-bar").css("width", percentComplete+"%");
                            $(".progreslogo .progress-bar").attr("aria-valuenow", percentComplete);
                        }
                    }, false );
                    return jqXHR;
                },
                success    : function ( data )
                {
					$(".logouploadbtn").show("slow");
					$(".progreslogo").hide();
					var res = eval("("+data+")");
					updateToken(res.token);
					if(res.success == true){
						$("#logo").attr("src","<?=base_url('uploads/logo/')?>"+res.filename);
					}
                }
            } );
		});
	});
</script>