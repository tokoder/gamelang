<?php
$set = $this->setting->globalset("semua");
$read = ($this->setting->demo() == true) ? "readonly" : "";
?>
<?=form_open('', 'id="pengaturan"');?>
	<div class="row">
		<div class="col-md-6 m-b-20">
			<!-- <div class="form-group titel" style="font-weight: bold;">
				PENGATURAN LOGIN OTP
			</div>
			<div class="btn-group g-otp col-12 m-lr-0 form-group m-b-10 col-md-6 m-b-30" role="group">
				<?php
				$setaktif = ($set->login_otp == 1) ? "btn-success" : "btn-light";
				$setnonaktif = ($set->login_otp == 0) ? "btn-success" : "btn-light";
				?>
				<button id="aktifotp" onclick="saveOTP(1)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?= $setaktif ?>"><b>OTP</b></button>
				<button id="aktifmanual" onclick="saveOTP(0)" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?= $setnonaktif ?>"><b>MANUAL</b></button>
			</div> -->
			<div class="form-group titel" style="font-weight: bold;">
				PENGATURAN SERVER EMAIL
			</div>
			<div class="form-group">
				<label>Metode Pengiriman</label>
				<select class="form-control col-6" name="email_jenis" <?= $read ?>>
					<option value="1" <?php if ($set->email_jenis == 1) { echo "selected"; } ?>>sendMail()</option>
					<option value="2" <?php if ($set->email_jenis == 2) { echo "selected"; } ?>>SMTP</option>
				</select>
			</div>
			<div class="form-group">
				<label>Email Pengirim Notifikasi</label>
				<input type="text" name="email_notif" class="form-control" value="<?= $set->email_notif ?>" <?= $read ?> />
			</div>
			<div class="form-group">
				<label>Password Email</label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="password" name="email_password" class="form-control col-6" value="abcdefghijk1234567890" <?= $read ?> />
				<?php } else { ?>
					<input type="password" name="email_password" class="form-control col-6" value="<?= $set->email_password ?>" />
				<?php } ?>
			</div>
			<div class="form-group">
				<label>Mail Server Domain</label>
				<input type="text" name="email_server" class="form-control col-10 col-md-8" value="<?= $set->email_server ?>" <?= $read ?> />
			</div>
			<div class="form-group">
				<label>Mail Server Port</label>
				<input type="text" name="email_port" class="form-control col-6 col-md-3" value="<?= $set->email_port ?>" <?= $read ?> />
			</div>
		</div>
		<div class="col-md-6 m-b-20">
			<!-- <div class="form-group titel" style="font-weight: bold;">
				PENGATURAN API WHATSAPP
			</div>
			<div class="btn-group g-vendor col-12 m-lr-0 form-group m-b-10 col-md-6" role="group">
				<?php
				$setaktif = ($set->api_wasap == "woowa") ? "btn-success" : "btn-light";
				$setnonaktif = ($set->api_wasap == "wablas") ? "btn-success" : "btn-light";
				?>
				<button id="aktifwoowa" onclick="saveApiWasap('woowa')" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?= $setaktif ?>"><b>WOOWA</b></button>
				<button id="aktifwablas" onclick="saveApiWasap('wablas')" type="button" style="border: 1px solid #bbb;" class="col-6 btn btn-sm <?= $setnonaktif ?>"><b>WABLAS</b></button>
			</div> -->
			<!-- <div class="form-group">
				<label>API Key <b>WooWA</b> (<a href="https://woowa.com/" target="_blank">woowa.com</a>)</label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="text" name="woowa" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?= $read ?> />
				<?php } else { ?>
					<input type="text" name="woowa" class="form-control" value="<?= $set->woowa ?>" />
				<?php } ?>
				<small><i class="text-danger">kosongkan apabila ingin menonaktifkan notifikasi Whatsapp</i></small><br />
			</div> -->
			<!-- <div class="form-group">
				<label>API Key <b>Wablas</b> (<a href="https://wablas.com/" target="_blank">wablas.com</a>)</label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="text" name="wablas" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?= $read ?> />
				<?php } else { ?>
					<input type="text" name="wablas" class="form-control" value="<?= $set->wablas ?>" />
				<?php } ?>
				<small><i class="text-danger">kosongkan apabila ingin menonaktifkan notifikasi Whatsapp</i></small><br />
			</div>
			<div class="form-group">
				<label>Domain Server <b>Wablas</b></label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="text" name="wablas_server" class="form-control" value="https://domain.wablas.com" <?= $read ?> />
				<?php } else { ?>
					<input type="text" name="wablas_server" class="form-control" value="<?= $set->wablas_server ?>" />
				<?php } ?>
			</div> -->
			<div class="form-group titel m-t-30" style="font-weight: bold;">
				PENGATURAN API RAJA ONGKIR
			</div>
			<div class="form-group">
				<label>API Key <b>Raja Ongkir PRO</b></label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="text" name="rajaongkir" class="form-control" value="abcdefghijklmnopqrstuvwxyz1234567890" <?= $read ?> />
				<?php } else { ?>
					<input type="text" name="rajaongkir" class="form-control" value="<?= $set->rajaongkir ?>" />
				<?php } ?>
			</div>
			<div class="form-group titel m-t-30" style="font-weight: bold;">
				PENGATURAN API LAINNYA
			</div>
			<div class="form-group">
				<label>Facebook Pixel ID</label>
				<?php if ($this->setting->demo() == true) { ?>
					<input type="text" name="fb_pixel" class="form-control" value="21333" <?= $read ?> />
				<?php } else { ?>
					<input type="text" name="fb_pixel" class="form-control" value="<?= $set->fb_pixel ?>" />
				<?php } ?>
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
	function saveApiWasap(val) {
		<?php
		if ($this->setting->demo() == true) {
			echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
		} else {
			echo '
					$(".g-vendor button").removeClass("btn-success");
					$(".g-vendor button").removeClass("btn-light");
					$.post("' . site_url('api/v1/setting/save') . '",{"api_wasap":val,[$("#names").val()]:$("#tokens").val()},function(ev){
						var data = eval("("+ev+")");
						updateToken(data.token);
						if(val == "woowa"){
							$("#aktifwoowa").addClass("btn-success");
							$("#aktifwablas").addClass("btn-light");
						}else{
							$("#aktifwoowa").addClass("btn-light");
							$("#aktifwablas").addClass("btn-success");
						}
					});
				';
		}
		?>
	}

	function saveOTP(val) {
		<?php
		if ($this->setting->demo() == true) {
			echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
		} else {
			echo '
					$(".g-otp button").removeClass("btn-success");
					$(".g-otp button").removeClass("btn-light");
					$.post("' . site_url('api/v1/setting/save') . '",{"login_otp":val,[$("#names").val()]:$("#tokens").val()},function(ev){
						var data = eval("("+ev+")");
						updateToken(data.token);
						if(val == 1){
							$("#aktifotp").addClass("btn-success");
							$("#aktifmanual").addClass("btn-light");
						}else{
							$("#aktifotp").addClass("btn-light");
							$("#aktifmanual").addClass("btn-success");
						}
					});
				';
		}
		?>
	}
	$(function() {
		$("#pengaturan").on("submit", function(e) {
			e.preventDefault();
			<?php
			if ($this->setting->demo() == true) {
				echo 'swal.fire("Mode Demo Terbatas","maaf, fitur tidak tersedia untuk mode demo","error");';
			} else {
				echo '
					var datar = $(this).serialize();
					datar = datar +  "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("' . site_url("api/v1/setting/save") . '",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							swal.fire("Berhasil","berhasil menyimpan pengaturan umum","success").then((val)=>{
								loadSettingServer();
							});
						}else{
							swal.fire("Gagal","gagal menyimpan pengaturan","error");
						}
					});';
			}
			?>
		});
	});
</script>