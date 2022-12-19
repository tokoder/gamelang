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
if ($rekening->num_rows() <= 10) {
?>
	<div class="row m-t-10">
		<div class="col-md-6 hidesmall font-bold color1">
			<h4>Daftar Rekening</h4>
		</div>
		<div class="col-md-6 text-right m-b-20">
			<a href="javascript:tambahRekening();" class="btn btn-success">
				<i class="fas fa-plus"></i> &nbsp;Tambah Rekening
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
			<th>No Rekening</th>
			<th>Atasnama</th>
			<th>Bank</th>
			<th>Kantor Cabang</th>
			<th></th>
		</tr>

		<?php
		$no = 1;
		foreach ($rekening->result() as $res) {
		?>
			<tr class="table_row">
				<td class="p-lr-20 p-tb-10">
					<p><?php echo $no; ?></p>
				</td>
				<td>
					<p><?php echo $res->norek; ?></p>
				</td>
				<td>
					<p><?php echo $res->atasnama; ?></p>
				</td>
				<td>
					<p>BANK <?php echo $this->setting->getBank($res->idbank, "nama"); ?></p>
				</td>
				<td>
					<p><?php echo $res->kcp; ?></p>
				</td>
				<td>
					<a href="javascript:editRekening(<?php echo $res->id; ?>)" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
					<a href="javascript:hapusRekening(<?php echo $res->id; ?>)" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash-alt"></i></a>
				</td>
			</tr>
		<?php
			$no++;
		}
		if ($rekening->num_rows() == 0) {
			echo "<tr><td class='center' colspan=6>
					<i class='fas fa-exclamation-triangle text-danger'></i> Belum ada daftar rekening, silahkan tambah data untuk menarik saldo.
					</td></tr>";
		}
		?>
	</table>
</div>

<!-- Modal3-Tambah Rekening -->
<div class="modal fade" id="tambahrekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi Rekening Bank</h5>
                <button type="button" class="btn" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-danger fs-24 p-all-2"></i>
                </button>
            </div>
            <div class="modal-body p-tb-40">
                <?=form_open('', 'class="form-horizontal"');?>
                    <input type="hidden" name="id" id="rekeningid" value="0" />
                    <div class="p-b-20 p-lr-30">
                        <div class="form-group">
                            <label>Bank</label>
                            <div class="m-b-12 rs1-select2 rs2-select2">
                                <select class="js-select2 form-control" id="rekeningidbank" name="idbank" required>
                                    <option value="">Pilih Bank</option>
                                    <?php
                                    foreach ($rekening_bank->result() as $res) {
                                        echo "<option value='" . $res->id . "'>" . $res->nama . "</option>";
                                    }
                                    ?>
                                </select>
                                <div class="dropDownSelect2"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>No Rekening</label>
                            <input class="form-control" id="rekeningnorek" type="text" name="norek" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Atas Nama</label>
                            <input class="form-control" id="rekeningatasnama" type="text" name="atasnama" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Kantor Cabang</label>
                            <input class="form-control" id="rekeningkcp" type="text" name="kcp" placeholder="" required>
                        </div>
                    </div>
                    <div class="p-lr-30">
                        <button type="submit" class="submitbutton btn btn-lg btn-success btn-block">
                            Simpan Rekening
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// REKENING
function tambahRekening() {
	$('.modal').modal("hide");
	$('#tambahrekening').modal();
}

$("#tambahrekening form").on("submit", function(e) {
	e.preventDefault();
	$(".submitbutton", this).parent().append(
		"<span class='cl1'><i class='fas fa-spin fa-compact-disc color1'></i> Memproses...</span>"
	);
	$(".submitbutton", this).hide();
	var submitbtn = $(".submitbutton", this);
	var datar = $(this).serialize();
	datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

	$.post("<?php echo site_url("account/rekening/tambah"); ?>", datar,
		function(msg) {
			var data = eval("(" + msg + ")");
			updateToken(data.token);
			if (data.success == true) {
				Swal.fire("Berhasil!", "Berhasil menambah rekening", "success").then((
					value) => {
					location.reload();
					//$("#navrekening").trigger("click");
				});
			} else {
				Swal.fire("Gagal!",
					"Gagal menambah rekening baru, silahkan ulangi beberapa saat lagi.",
					"error");
				submitbtn.show();
				submitbtn.parent().find("span").remove();
			}
		});
});
</script>