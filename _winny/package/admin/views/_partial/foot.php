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
	</div>
	<?php 
	$foot = array(
		'jquery-ui.min.js',
		'popper.min.js',
		'bootstrap.min.js',
		'moment.min.js',
		'bootstrap-datetimepicker.js', 
		'chartist/chartist.min.js', 
		'chartist/plugin/chartist-plugin-tooltip.min.js', 
		'bootstrap-notify/bootstrap-notify.min.js', 
		'bootstrap-toggle/bootstrap-toggle.min.js', 
		'jquery-mapael/jquery.mapael.min.js', 
		'chart-circle/circles.min.js', 
		'sweetalert2.js', 
		'ready.min.js',
	);
	$this->load->add_assets($foot, 'foot');
	echo $this->load->get_assets('foot');
	?>
	<script type="text/javascript">
		$(function(){
			$("#userpass").on("submit",function(e){
				e.preventDefault();
				if($("#usrpass").val() == $("#usrpass2").val()){
					swal.fire({
						text: "pastikan lagi data yang anda masukkan sudah sesuai",
						title: "Validasi data",
						type: "warning",
						showCancelButton: true,
						cancelButtonText: "Cek Lagi"
					}).then((vals)=>{
						if(vals.value){
							var datar = $(this).serialize();
							datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
							$.post("<?=site_url("api/v1/user/tambah")?>",datar,function(msg){
								var data = eval("("+msg+")");
								updateToken(data.token);
								if(data.success == true){
									$("#modalgantipass").modal("hide");
									swal.fire("Berhasil","data user sudah disimpan","success");
								}else{
									swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
								}
							});
						}
					});
				}else{
					swal.fire("Cek Password","password yang Anda masukkan tidak sesuai, pastikan isi formulirnya dengan benar","error");
				}
			});
		});
	
		function logout(){
			swal.fire({
				text: "Anda yakin akan keluar?",
				title: "Logout",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Batal"
			}).then((vals)=>{
				if(vals.value == true){
					window.location.href = "<?=site_url("admin/auth/logout")?>";
				}
			});
		}
		
		function updateToken(token){
			$("#tokens,.tokens").val(token);
		}
	</script>

    <?php echo $custom_js; ?>
</body>
</html>