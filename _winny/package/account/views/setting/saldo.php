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
<div class="col-md-8 m-lr-auto">
	<div class="p-tb-10">
		<h4 class="font-medium mb-2">Saldo Saat Ini</h4>
		<h2 class="font-bold text-success m-t-20"><b>Rp
				<?php echo $this->setting->formUang($this->user_model->getSaldo($_SESSION["uid"], "saldo", "usrid")); ?>,-</b>
		</h2>
	</div>
</div>
<div class="col-md-4 m-lr-auto">
	<div class="p-tb-10">
		<a href="javascript:topupSaldo()" class="btn btn-dark btn-sm btn-block m-b-10">
			<i class="fas fa-chevron-circle-up"></i>&nbsp; Top Up
		</a>
		<a href="javascript:tarikSaldo()" class="btn btn-outline-dark btn-sm btn-block">
			<i class="fas fa-chevron-circle-down"></i>&nbsp; Tarik Saldo
		</a>
	</div>
</div>
</div>

Riwayat  Topup
<div class="fancy-title title-border">
<h4>Riwayat Top Up Saldo</h4>
</div>
<div id="loadhistorytopup"></div>

Riwayat  Tarik
<div class="fancy-title title-border">
<h4>Transaksi Terakhir</h4>
</div>
<div id="loadhistorysaldo"></div>

<!-- Modal3-Topup Saldo -->
<div class="modal fade" id="topupsaldo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Top Up Saldo</h5>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-danger fs-24 p-all-2"></i>
                </button>
            </div>
            <div class="modal-body">
                <?=form_open(site_url("account/saldo"));?>
                    <div class="col-md-12 p-b-30 m-t-30 p-lr-30">
                        <div class="form-group">
                            <label for="jumlahtopup">Jumlah</label>
                            <input class="form-control fs-20 font-medium" type="text" id="jumlahtopup" name="jumlah" placeholder="Rp" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-6 col-md-3 m-t-10">
                                <button type="button" class="btn btn-info btn-block" onclick="$('#jumlahtopup').val(50000)">50.000</button>
                            </div>
                            <div class="col-6 col-md-3 m-t-10">
                                <button type="button" class="btn btn-info btn-block" onclick="$('#jumlahtopup').val(100000)">100.000</button>
                            </div>
                            <div class="col-6 col-md-3 m-t-10">
                                <button type="button" class="btn btn-info btn-block" onclick="$('#jumlahtopup').val(150000)">150.000</button>
                            </div>
                            <div class="col-6 col-md-3 m-t-10">
                                <button type="button" class="btn btn-info btn-block" onclick="$('#jumlahtopup').val(200000)">200.000</button>
                            </div>
                        </div>
                        <div class="form-group m-t-36">
                            <button type="submit" class="submitbutton btn btn-success btn-block btn-lg">
                                Topup Saldo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal3-Tarik Saldo -->
<div class="modal fade" id="tariksaldo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penarikan Saldo</h5>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-danger fs-24 p-all-2"></i>
                </button>
            </div>
            <div class="modal-body p-tb-40">
                <?=form_open();?>
                    <div class="p-b-10 p-lr-30">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>Jumlah</label>
                            </div>
                            <div class="col-md-8 m-b-12">
                                <input class="form-control" type="text" name="jumlah" placeholder="Rp" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>Rekening Tujuan</label>
                            </div>
                            <div class="col-md-8">
                                <div class="m-b-12 rs1-select2 rs2-select2">
                                    <select class="js-select2 form-control" id="rekeningchange" name="idrek" required>
                                        <option value="" id='defaultrek'>Pilih Rekening</option>
                                        <?php
                                        foreach ($rekening->result() as $res) {
                                            echo "<option value='" . $res->id . "'>" . $res->norek . " - " . $res->atasnama . "</option>";
                                        }
                                        ?>
                                        <option value="0">+ Tambah Rekening Baru</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>Catatan</label>
                            </div>
                            <div class="col-md-8 m-b-12">
                                <input class="form-control" type="text" name="keterangan" placeholder="Catatan">
                            </div>
                        </div>
                    </div>
                    <div class="p-lr-30">
                        <button type="submit" class="submitbutton btn btn-success btn-block btn-lg">
                            Tarik Saldo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    
$("#loadhistorysaldo").load("<?php echo site_url("account/history/tarik"); ?>");
$("#loadhistorytopup").load("<?php echo site_url("account/history/topup"); ?>");

$("#tariksaldo form").on("submit", function(e) {
    e.preventDefault();
    $(".submitbutton", this).parent().append(
        "<span class='cl1'><i class='fas fa-spin fa-compact-disc color1'></i> Memproses...</span>"
    );
    $(".submitbutton", this).hide();
    var submitbtn = $(".submitbutton", this);
    var datar = $(this).serialize();
    datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

    $.post("<?php echo site_url("account/saldo"); ?>", datar,
        function(msg) {
            var data = eval("(" + msg + ")");
            updateToken(data.token);
            if (data.success == true) {
                Swal.fire("Berhasil!",
                    "Berhasil menarik saldo, tunggu maks. 2 hari kerja sampai uang Anda masuk ke rekening",
                    "success").then((value) => {
                    location.reload();
                });
            } else {
                Swal.fire("Gagal!", data.msg, "error");
                submitbtn.show();
                submitbtn.parent().find("span").remove();
            }
        });
});

$("#topupsaldo form").on("submit", function(e) {
    e.preventDefault();
    $(".submitbutton", this).parent().append(
        "<span class='cl1'><i class='fas fa-spin fa-compact-disc color1'></i> Memproses...</span>"
    );
    $(".submitbutton", this).hide();
    var submitbtn = $(".submitbutton", this);
    var datar = $(this).serialize();
    datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

    $.post("<?php echo site_url("account/saldo"); ?>", datar,
        function(msg) {
            var data = eval("(" + msg + ")");
            updateToken(data.token);
            if (data.success == true) {
                window.location.href = "<?= site_url(" account/saldo ") ?>?inv=" + data.idbayar;
            } else {
                Swal.fire("Gagal!", data.msg, "error");
                submitbtn.show();
                submitbtn.parent().find("span").remove();
            }
        });
});

// MANAJEMEN SALDO
function topupSaldo() {
    $('#topupsaldo').modal();
}

function tarikSaldo() {
    $('#tariksaldo').modal();
}
</script>