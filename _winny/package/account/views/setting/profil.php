<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */
?>
<div class="row">
	<div class="col-md-6 m-lr-auto m-tb-10">
		<h4 class="m-b-20 font-bold color1">
			Profil Pengguna
		</h4>
		<?= form_open('', 'class="form-horizontal" id="profil"'); ?>
		<div class="form-group m-b-12">
			<label>Nama</label>
			<input class="form-control" type="text" name="nama" value="<?php echo $profil->nama; ?>">
		</div>
		<div class="form-group m-b-12">
			<label>Email</label>
			<input class="form-control" type="text" name="email" value="<?php echo $user->username; ?>" disabled>
		</div>
		<div class="form-group m-b-12">
			<label>No Handphone</label>
			<input class="form-control" type="text" name="nohp" value="<?php echo $profil->nohp; ?>">
		</div>
		<div class="form-group m-b-12">
			<label>Kelamin</label>
			<div class="rs1-select2 rs2-select2">
				<select class="js-select2 form-control" name="kelamin">
					<option value="">Kelamin</option>
					<option value="1" <?php if ($profil->kelamin == 1) {
											echo "selected";
										} ?>>Laki-laki</option>
					<option value="2" <?php if ($profil->kelamin == 2) {
											echo "selected";
										} ?>>Perempuan</option>
				</select>
				<div class="dropDownSelect2"></div>
			</div>
		</div>
		<div class="form-group m-t-50">
			<a href="javascript:void(0)" onclick="simpanProfil()" class="btn btn-success btn-block btn-lg">
				<i class="fas fa-check-circle"></i> &nbsp;Simpan Profil
			</a>
			<span class="btn btn-success btn-block btn-lg" id="profilload" style="display:none;"><i class='fas fa-spin fa-compact-disc text-success'></i>
				Menyimpan...</span>
		</div>
		</form>
	</div>

	<div class="col-md-6 m-lr-auto p-lr-0 m-tb-10">
		<h4 class="m-b-20 font-bold color1">
			Ganti Password
		</h4>
		<?= form_open('', 'class="form-horizontal" id="gantipassword"'); ?>
		<div class="form-group m-b-12">
			<label>Password Baru</label>
			<input class="form-control" type="password" name="password" value="">
		</div>
		<div class="form-group m-b-12">
			<label>Ulangi Password</label>
			<input class="form-control" type="password" value="">
		</div>
		<div class="form-group m-t-10">
			<a href="javascript:void(0)" onclick="simpanPassword()" class="btn btn-success btn-block btn-lg">
				<i class="fas fa-check-circle"></i> &nbsp;Simpan Password
			</a>
			<span id="passwload" style="display:none;"><i class='fas fa-spin fa-compact-disc text-success'></i>
				Menyimpan...</span>
		</div>
		</form>
	</div>
</div>

<script>
// MANAJEMEN USER
function simpanProfil() {
	$("#profil a").hide();
	$("#profilload").show();
	var datar = $("#profil").serialize();
	datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

	$.post("<?php echo site_url("account/setting/profil"); ?>", datar,
		function(msg) {
			var data = eval("(" + msg + ")");
			updateToken(data.token);
			$("#profil a").show();
			$("#profilload").hide();
			if (data.success == true) {
				Swal.fire({
						title: "Berhasil",
						text: "Berhasil menyimpan informasi pengguna",
						icon: "success",
						showDenyButton: false,
						confirmButtonText: "Oke",
					})
					.then((result) => {
						if (result.isConfirmed) {
							location.reload(true);
						}
					});
			} else {
				Swal.fire("Gagal!", "Gagal menyimpan informasi pengguna", "error");
			}
		});
}
function simpanPassword() {
	$("#gantipassword a").hide();
	$("#passwload").show();
	var datar = $("#gantipassword").serialize();
	datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

	$.post("<?php echo site_url("account/setting/pass"); ?>", datar,
		function(msg) {
			var data = eval("(" + msg + ")");
			updateToken(data.token);
			$("#gantipassword a").show();
			$("#passwload").hide();
			if (data.success == true) {
				$("#gantipassword input").val("");
				Swal.fire({
						title: "Berhasil",
						text: "Berhasil menyimpan password baru",
						icon: "success",
						showDenyButton: false,
						confirmButtonText: "Oke",
					})
					.then((result) => {
						if (result.isConfirmed) {
							location.reload(true);
						}
					});
			} else {
				Swal.fire("Gagal!", "Gagal menyimpan informasi password", "error");
			}
		});
}
</script>