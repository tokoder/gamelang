<h4 class="page-title">Pengaturan </h4>

<div class="m-b-60">
    <div class="card">
        <div class="card-header row">
            <div class="tabs p-lr-15 m-b-10 col-md-10">
                <!-- <a href="javascript:void(0)"
                    onclick="loadSettingUmum();$('.tambuser').hide();$('.tambank').hide();$('.tambwas').hide();"
                    class="tabs-item active">
                    <i class="fas fa-clipboard-check"></i> Umum
                </a> -->
                <a href="javascript:void(0)"
                    onclick="loadSettingPayment();$('.tambuser').hide();$('.tambwas').hide();$('.tambank').hide();"
                    class="tabs-item">
                    <i class="fas fa-cash-register"></i> Payment
                </a>
                <a href="javascript:void(0)"
                    onclick="loadSettingServer();$('.tambuser').hide();$('.tambwas').hide();$('.tambank').hide();"
                    class="tabs-item">
                    <i class="fas fa-server"></i> Server
                </a>
                <a href="javascript:void(0)"
                    onclick="loadSettingKurir();$('.tambuser').hide();$('.tambwas').hide();$('.tambank').hide();"
                    class="tabs-item">
                    <i class="fas fa-shipping-fast"></i> Kurir
                </a>
                <a href="javascript:void(0)"
                    onclick="loadWasap(1);$('.tambuser').hide();$('.tambank').hide();$('.tambwas').show();"
                    class="tabs-item">
                    <i class="fab fa-whatsapp"></i> Whatsapp
                </a>
                <a href="javascript:void(0)"
                    onclick="loadSettingBank(1);$('.tambuser').hide();$('.tambank').show();$('.tambwas').hide();"
                    class="tabs-item">
                    <i class="fas fa-money-check-alt"></i> Rekening
                </a>
                <?php if ($_SESSION["gid"] == 2) { ?>
                <a href="javascript:void(0)"
                    onclick="loadUser(1);$('.tambuser').show();$('.tambwas').hide();$('.tambank').hide();"
                    class="tabs-item">
                    <i class="fas fa-users-cog"></i> Pengguna
                </a>
                <?php } ?>
            </div>
            <div class="col-md-2 tambank" style="display:none;">
                <button class="btn btn-block btn-primary" onclick="tambahRekening()"><i class="fas fa-plus-circle"></i>
                    &nbsp;Rekening</button>
            </div>
            <div class="col-md-2 tambuser" style="display:none;">
                <button class="btn btn-block btn-primary" onclick="tambahUser()"><i class="fas fa-plus-circle"></i>
                    &nbsp;Pengguna</button>
            </div>
            <div class="col-md-2 tambwas" style="display:none;">
                <button class="btn btn-block btn-primary" onclick="tambahWasap()"><i class="fas fa-plus-circle"></i>
                    &nbsp;Admin</button>
            </div>
        </div>
        <div class="card-body" id="load">
            <i class="fas fa-spin fa-spinner"></i> Loading data...
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    loadSettingUmum();

    $(".tabs-item").on('click', function() {
        $(".tabs-item.active").removeClass("active");
        $(this).addClass("active");
    });

    $("#wasapform").on("submit", function(e) {
        e.preventDefault();
        swal.fire({
            text: "pastikan lagi data yang anda masukkan sudah sesuai",
            title: "Validasi data",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cek Lagi"
        }).then((vals) => {
            if (vals.value) {
                var datar = $("#wasapform").serialize();
                datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
                $.post("<?= site_url("api/v1/wasap/tambah") ?>", datar, function(msg) {
                    var data = eval("(" + msg + ")");
                    updateToken(data.token);
                    if (data.success == true) {
                        loadWasap(1);
                        $("#modalwasap").modal("hide");
                        swal.fire("Berhasil", "data sudah disimpan", "success");
                    } else {
                        swal.fire("Gagal!",
                            "gagal menyimpan data, coba ulangi beberapa saat lagi",
                            "error");
                    }
                });
            }
        });
    });

    $("#rekeningform").on("submit", function(e) {
        e.preventDefault();
        swal.fire({
            text: "pastikan lagi data yang anda masukkan sudah sesuai",
            title: "Validasi data",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cek Lagi"
        }).then((vals) => {
            if (vals.value) {
                var datar = $("#rekeningform").serialize();
                datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
                $.post("<?= site_url("api/v1/rekening/tambah") ?>", datar, function(msg) {
                    var data = eval("(" + msg + ")");
                    updateToken(data.token);
                    if (data.success == true) {
                        loadSettingBank(1);
                        $("#modal").modal("hide");
                        swal.fire("Berhasil", "data rekening sudah disimpan",
                            "success");
                    } else {
                        swal.fire("Gagal!",
                            "gagal menyimpan data, coba ulangi beberapa saat lagi",
                            "error");
                    }
                });
            }
        });
    });

    <?php
		if ($this->setting->demo() != true) {
			echo '
				$("#userform").on("submit",function(e){
					e.preventDefault();
					swal.fire({
						text: "pastikan lagi data yang anda masukkan sudah sesuai",
						title: "Validasi data",
						type: "warning",
						showCancelButton: true,
						cancelButtonText: "Cek Lagi"
					}).then((vals)=>{
						if(vals.value){
							var datar = $("#userform").serialize();
							datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
							$.post("' . site_url("api/v1/user/tambah") . '",datar,function(msg){
								var data = eval("("+msg+")");
								updateToken(data.token);
								if(data.success == true){
									loadUser(1);
									$("#modaluser").modal("hide");
									swal.fire("Berhasil","data user sudah disimpan","success");
								}else{
									swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
								}
							});
						}
					});
				});';
		}
		?>
});

function loadSettingBank(page) {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $("#load").load("<?= site_url("admin/setting/rekening?load=setting&page=") ?>" + page);
}

function loadWasap(page) {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $("#load").load("<?= site_url("admin/setting/wasap?load=wasap&page=") ?>" + page);
}

// function loadSettingUmum() {
// 	$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
// 	$("#load").load("<?= site_url('admin/setting/form') ?>");
// }

function loadSettingKurir() {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $("#load").load("<?= site_url('admin/setting/kurir') ?>");
}

function loadSettingServer() {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $("#load").load("<?= site_url('admin/setting/server') ?>");
}

// function loadSettingPayment() {
//     $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
//     $("#load").load("<?= site_url('admin/setting/payment') ?>");
// }

function loadUser(page) {
    $("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
    $.post("<?= site_url('admin/user/list') ?>?page=" + page, {
        [$("#names").val()]: $("#tokens").val()
    }, function(ev) {
        var data = eval("(" + ev + ")");
        updateToken(data.token);
        $("#load").html(data.result);
    });
}

function editWasap(id) {
    $.post("<?= site_url('admin/wasap') ?>", {
        "formid": id,
        [$("#names").val()]: $("#tokens").val()
    }, function(ev) {
        var data = eval("(" + ev + ")");
        updateToken(data.token);
        $("#wasid").val(id);
        $("#wasnama").val(data.nama);
        $("#waswasap").val(data.wasap);

        $("#modalwasap").modal();
    });
}

function edit(id) {
    $.post("<?= site_url('admin/rekening') ?>", {
        "formid": id,
        [$("#names").val()]: $("#tokens").val()
    }, function(ev) {
        var data = eval("(" + ev + ")");
        updateToken(data.token);
        $("#rekid").val(id);
        $("#reknama").val(data.atasnama);
        $("#reknorek").val(data.norek);
        $("#rekkcp").val(data.kcp);

        $("#rekbank option").each(function() {
            if ($(this).val() == data.idbank) {
                $(this).prop("selected", true);
            } else {
                $(this).prop("selected", false);
            }
        });

        $("#modal").modal();
    });
}
<?php
	if ($this->setting->demo() != true) {
		echo '
			function editUser(id){
				$.post("' . site_url('admin/user/list') . '",{"formid":id,[$("#names").val()]:$("#tokens").val()},function(ev){
					var data = eval("("+ev+")");
					updateToken(data.token);
					$("#usrid").val(id);
					$("#usrnama").val(data.nama);
					$("#usrpass").val(data.pass);
					$("#usrusername").val(data.username);

					$("#usrlevel option").each(function(){
						if($(this).val() == data.level){
							$(this).prop("selected",true);
						}else{
							$(this).prop("selected",false);
						}
					});

					$("#modaluser").modal();
				});
			}
			function hapusUser(id){
				swal.fire({
					text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
					title: "Yakin menghapus data ini?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: "#ff646d",
					cancelButtonText: "Batal"
				}).then((vals)=>{
					if(vals.value){
						$.post("' . site_url("api/v1/user/hapus") . '",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
							var data = eval("("+msg+")");
							updateToken(data.token);
							if(data.success == true){
								loadUser(1);
								swal.fire("Berhasil","data sudah dihapus","success");
							}else{
								swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
							}
						});
					}
				});
			}';
	}
	?>

function tambahUser() {
    <?php
		if ($this->setting->demo() != true) {
			echo "
				$('#userform')[0].reset();
				$('#usrid').val(0);";
		}
		?>

    $("#modaluser").modal();
}

function tambahRekening() {
    $('#rekeningform')[0].reset();
    $('#rekid').val(0);

    $("#modal").modal();
}

function tambahWasap() {
    $('#wasapform')[0].reset();
    $('#wasid').val(0);

    $("#modalwasap").modal();
}

function hapus(id) {
    swal.fire({
        text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
        title: "Yakin menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#ff646d",
        cancelButtonText: "Batal"
    }).then((vals) => {
        if (vals.value) {
            $.post("<?= site_url("api/v1/rekening/hapus") ?>", {
                "id": id,
                [$("#names").val()]: $("#tokens").val()
            }, function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                if (data.success == true) {
                    loadSettingBank(1);
                    swal.fire("Berhasil", "data sudah dihapus", "success");
                } else {
                    swal.fire("Gagal!", "gagal menghapus data, coba ulangi beberapa saat lagi",
                        "error");
                }
            });
        }
    });
}

function hapusWasap(id) {
    swal.fire({
        text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
        title: "Yakin menghapus data ini?",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#ff646d",
        cancelButtonText: "Batal"
    }).then((vals) => {
        if (vals.value) {
            $.post("<?= site_url("api/v1/wasap/hapus") ?>", {
                "id": id,
                [$("#names").val()]: $("#tokens").val()
            }, function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                if (data.success == true) {
                    loadUser(1);
                    swal.fire("Berhasil", "data sudah dihapus", "success");
                } else {
                    swal.fire("Gagal!", "gagal menghapus data, coba ulangi beberapa saat lagi",
                        "error");
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
                <h6 class="modal-title">Pengaturan Rekening</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=form_open('', 'id="rekeningform"');?>
                <input type="hidden" name="id" id="rekid" value="0" />
                <div class="form-group">
                    <label>Bank</label>
                    <select id="rekbank" name="idbank" class="form-control" required>
                        <option value="">- Pilih Bank -</option>
                        <?php
							$this->db->order_by("nama");
							$db = $this->db->get("@rekening_bank");
							foreach ($db->result() as $r) {
								echo "<option value='" . $r->id . "'>" . $r->nama . "</option>";
							}
							?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kantor Cabang</label>
                    <input type="text" id="rekkcp" name="kcp" placeholder="cth: KCP Sriwedari, KCU Solo"
                        class="form-control" required />
                </div>
                <div class="form-group">
                    <label>No Rekening</label>
                    <input type="text" id="reknorek" name="norek" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Atasnama</label>
                    <input type="text" id="reknama" name="atasnama" class="form-control" required />
                </div>
                <div class="form-group m-tb-10">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>
                        Batal</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modaluser" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Pengaturan User</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
				if ($this->setting->demo() == true) {
					echo 'maaf, fitur tidak terseida untuk mode demo aplikasi';
				} else {
					echo
						form_open('', 'id="userform"').'
							<input type="hidden" name="id" id="usrid" value="0" />
							<div class="form-group">
								<label>Nama</label>
								<input type="text" id="usrnama" name="nama" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Username</label>
								<input type="text" id="usrusername" name="username" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="password" id="usrpass" name="pass" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Level</label>
								<select id="usrlevel" name="level" class="form-control" required >
									<option value="">- Pilih Level -</option>
									<option value="1">Admin</option>
									<option value="2">Owner</option>
								</select>
							</div>
							<div class="form-group m-tb-10">
								<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
							</div>
						</form>';
				}
				?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalwasap" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Pengaturan Admin Whatsapp</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=form_open('', 'id="wasapform"');?>
                <input type="hidden" name="id" id="wasid" value="0" />
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" id="wasnama" name="nama" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Nomer Whatsapp</label>
                    <input type="text" id="waswasap" name="wasap" class="form-control" required />
                </div>
                <div class="form-group m-tb-10">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i>
                        Batal</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>