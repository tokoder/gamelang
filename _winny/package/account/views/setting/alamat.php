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

<?php
$this->db->where("usrid", $_SESSION["uid"]);
$db = $this->db->get("user_alamat");

if ($db->num_rows() <= 10) {
?>
	<div class="row m-t-10">
		<div class="col-6 hidesmall font-bold color1">
			<h4>Daftar Alamat</h4>
		</div>
		<div class="col-6 text-right m-b-20">
			<a href="javascript:tambahAlamat();" class="btn btn-success">
				<i class="fas fa-plus"></i> &nbsp;Tambah Alamat
			</a>
		</div>
	</div>
<?php
}
?>

<div class="table-responsive">
	<table class="table table-hover table-bordered table-striped">
		<tr class="table_head">
			<th class="p-l-20">#</th>
			<th>Nama Penerima</th>
			<th>No Handphone</th>
			<th>Alamat</th>
			<th></th>
		</tr>

		<?php
		$no = 1;
		foreach ($db->result() as $res) {
		?>
			<tr class="table_row">
				<td class="p-lr-20 p-tb-10">
					<?php echo $res->judul; ?>
					<?php if ($res->status == 1) {
						echo '<small class="badge badge-warning">Alamat Utama</small>';
					} ?>
				</td>
				<td>
					<?php echo $res->nama; ?>
				</td>
				<td>
					<?php echo $res->nohp; ?>
				</td>
				<td>
					<?php echo $res->alamat . "<small>Kodepos " . $res->kodepos . "</small>"; ?>
				</td>
				<td>
					<a href="javascript:editAlamat(<?php echo $res->id; ?>)" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
					<a href="javascript:hapusAlamat(<?php echo $res->id; ?>)" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash-alt"></i></a>
				</td>
			</tr>
		<?php
			$no++;
		}
		if ($db->num_rows() == 0) {
			echo "<tr><td class='center' colspan=6>
				<i class='fas fa-exclamation-triangle text-danger'></i> Belum ada daftar alamat, silahkan tambah data pengiriman pesanan.
			</td></tr>";
		}
		?>
	</table>
</div>

<!-- Modal3-Tambah Alamat -->
<div class="modal fade" id="tambahalamat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi Alamat</h5>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-danger fs-24 p-all-2"></i>
                </button>
            </div>
            <div class="modal-body p-tb-40">
                <?=form_open('', 'class="m-0"');?>
                    <input type="hidden" name="id" id="alamatid" value="0" />
                    <div class="p-b-15 p-lr-30">
                        <div class="form-group">
                            <label>Simpan sebagai? <small>cth: Alamat Rumah, Alamat Kantor, dll</small></label>
                            <input class="form-control" id="alamatjudul" type="text" name="judul" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Penerima</label>
                            <input class="form-control" id="alamatnama" type="text" name="nama" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>No Handphone</label>
                            <input class="form-control" id="alamatnohp" type="text" name="nohp" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <input class="form-control" id="alamatalamat" type="text" name="alamat" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Provinsi</label>
                            <div class="rs1-select2 rs2-select2">
                                <select class="js-select2 form-control" id="alamatprov" required>
                                    <option value="">Pilih Provinsi</option>
                                    <?php
                                    $prov = $this->rajaongkir->getProvinces()['rajaongkir']['results'];
                                    foreach ($prov as $res) {
                                        echo "<option value='" . $res['province_id'] . "'>" . $res['province'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Kabupaten</label>
                            <div class="rs1-select2 rs2-select2">
                                <select class="js-select2 form-control" id="alamatkab" name="idkab" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label>Kecamatan</label>
                            <div class="rs1-select2 rs2-select2">
                                <select class="js-select2 form-control" id="alamatkec" name="idkec" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label>Kodepos</label>
                            <input class="form-control" id="alamatkodepos" type="text" name="kodepos" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Simpan Sebagai</label>
                            <div class="rs1-select2 rs2-select2">
                                <select class="js-select2 form-control" id="alamatstatus" name="status" required>
                                    <option value="0">Alamat</option>
                                    <option value="1">Alamat Utama</option>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-lr-30">
                        <button type="submit" class="submitbutton btn btn-success btn-block btn-lg">
                            Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    $("#alamatprov").change(function() {
        if (localStorage["isedit"] != true) {
            changeKab($(this).val(), "");
        }
    });

    $("#alamatkab").change(function () {
        // if (localStorage["isedit"] != true) {
        //     changeKec($(this).val(), "");
        // }
        var postal_code = $(this).find(":selected").data('kodepos');
        $("#alamatkodepos").val(postal_code);
    });

    $("#tambahalamat form").on("submit", function(e) {
        e.preventDefault();
        $(".submitbutton", this).parent().append(
            "<span class='btn btn-success btn-block btn-lg'><i class='fas fa-spin fa-compact-disc color1'></i> Memproses...</span>"
        );
        $(".submitbutton", this).hide();
        var submitbtn = $(".submitbutton", this);
        var datar = $(this).serialize();
        datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

        $.post("<?php echo site_url("account/alamat/tambah"); ?>", datar,
            function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                if (data.success == true) {
                    Swal.fire("Berhasil!", "Berhasil menambah alamat", "success").then((value) => {
                        location.reload();
                        //$("#navalamat").trigger("click");
                    });
                } else {
                    Swal.fire("Gagal!",
                        "Gagal menambah alamat baru, silahkan ulangi beberapa saat lagi.",
                        "error");
                    submitbtn.show();
                    submitbtn.parent().find("span").remove();
                }
            });
    });
});

// FORM CHANGING
function changeProv(proval) {
	$("#alamatprov").val(proval).trigger("change");
}
function changeKab(proval, valu) {
	$("#alamatkab").html("<option value=''>Loading...</option>").trigger("change");
	$("#alamatkec").html("<option value=''>Kecamatan</option>").trigger("change");

	$.post("<?php echo site_url("regional/kabupaten"); ?>", {
		"id": proval,
		[$("#names").val()]: $("#tokens").val()
	},
	function(msg) {
		var data = eval("(" + msg + ")");
		updateToken(data.token);
		$("#alamatkab").html(data.html).promise().done(function() {
			$("#alamatkab").val(valu);
		})
	});
}
function changeKec(kabval, valu) {
	// $("#alamatkec").html("<option value=''>Loading...</option>").trigger("change");
	// $.post("<?php echo site_url("ongkir/getkec"); ?>", {
	//     "id": kabval,
	//     [$("#names").val()]: $("#tokens").val()
	// },
	// function (msg) {
	//     var data = eval("(" + msg + ")");
	//     updateToken(data.token);
	//     $("#alamatkec").html(data.html).promise().done(function () {
	//         $("#alamatkec").val(valu);
	//     });
	// });
}

// ALAMAT
function tambahAlamat() {
	localStorage["isedit"] = false;
	$("#alamatid").val(0);
	$("#alamatnama").val("");
	$("#alamatnohp").val("");
	$("#alamatstatus").val(0).trigger("change");
	$("#alamatalamat").val("");
	$("#alamatkodepos").val("");
	$("#alamatjudul").val("");
	$("#alamatprov").val("").trigger("change");
	$('.modal').modal("hide");
	$('#tambahalamat').modal();
}
function editAlamat(rek) {
	localStorage["isedit"] = true;
	$.post("<?php echo site_url("account/alamat"); ?>", {
		"rek": rek,
		[$("#names").val()]: $("#tokens").val()
	},
	function(msg) {
		var data = eval("(" + msg + ")");
		updateToken(data.token);

		if (data.success == true) {
			changeProv(data.prov),
			changeKab(data.prov, data.kab),
			// changeKec(data.kab, data.idkec);
			$("#alamatid").val(rek);
			$("#alamatnama").val(data.nama);
			$("#alamatnohp").val(data.nohp);
			$("#alamatstatus").val(data.status).trigger("change");
			$("#alamatalamat").val(data.alamat);
			$("#alamatkodepos").val(data.kodepos);
			$("#alamatjudul").val(data.judul);
			$('.modal').modal("hide");
			$('#tambahalamat').modal();
		} else {
			Swal.fire("Error!", "terjadi kesalahan silahkan ulangi beberapa saat lagi.", "error");
		}
	});
}
function hapusAlamat(rek) {
	Swal.fire({
			title: "Anda yakin?",
			text: "menghapus alamat ini dari akun Anda?",
			icon: "warning",
			showDenyButton: true,
			confirmButtonText: "Oke",
			denyButtonText: "Batal"
		})
		.then((willDelete) => {
			if (willDelete.isConfirmed) {
				$.post("<?php echo site_url("account/alamat/hapus"); ?>", {
						"rek": rek,
						[$("#names").val()]: $("#tokens").val()
					},
					function(msg) {
						var data = eval("(" + msg + ")");
						updateToken(data.token);

						if (data.success == true) {
							Swal.fire("Berhasil!", "Berhasil menghapus alamat", "success").then((value) => {
								location.reload();
							});
						} else {
							Swal.fire("Error!", "terjadi kesalahan silahkan ulangi beberapa saat lagi.",
								"error");
						}
					});
			}
		});
}
</script>