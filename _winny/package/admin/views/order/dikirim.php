<div class="table-responsive">
	<table class="table table-condensed table-hover">
		<tr>
			<th scope="col">Tanggal</th>
			<th scope="col">No Transaksi</th>
			<th scope="col">Nama Pembeli</th>
			<th scope="col">No Resi</th>
			<th scope="col">Kurir</th>
			<th scope="col">Ket</th>
			<th scope="col">Aksi</th>
		</tr>
		<?php
		$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
		$cari = (isset($_POST["cari"]) and $_POST["cari"] != "") ? $_POST["cari"] : "";
		$orderby = (isset($data["orderby"]) and $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$where = "orderid LIKE '%$cari%' OR total LIKE '%$cari%' OR kodebayar LIKE '%$cari%'";
		$this->db->like("orderid", $cari);
		$this->db->where("status", 2);
		$this->db->where("resi!=", "");
		$rows = $this->db->get("sales");
		$rows = $rows->num_rows();

		$this->db->like("orderid", $cari);
		$this->db->where("status", 2);
		$this->db->where("resi!=", "");
		$this->db->order_by("id", "DESC");
		$this->db->limit($perpage, ($page - 1) * $perpage);
		$db = $this->db->get("sales");

		if ($rows > 0) {
			$no = 1;
			foreach ($db->result() as $r) {
				$kurir = strtoupper($r->kurir . " " . $r->paket);
				$resi = ($r->resi != "") ? $r->resi . "<!-- <a href='javascript:void(0)' onclick='lacakPaket(\"" . $r->resi . "\")'>Lacak &gt;</a>-->" : "";
		?>
				<tr>
					<td class="text-center"><i class="fas fa-shipping-fast text-success blink"></i> &nbsp; <?= $this->setting->ubahTgl("d M Y H:i", $r->tgl); ?></td>
					<td><?= $r->orderid ?></td>
					<td><?= $this->user_model->getProfil($r->usrid, "nama", "usrid") ?></td>
					<td><?= $resi ?></td>
					<td><?= $kurir ?></td>
					<td><?= $r->keterangan ?></td>
					<td style="min-width:180px;">
						<a href="javascript:void(0)" onclick="inputResi(<?= $r->id ?>)" class="btn btn-warning btn-xs"><i class="fas fa-shipping-fast"></i> Update Resi</a>
						<?php if ($r->kurir != "bayar" and $r->kurir != "toko" and $r->kurir != "cod") { ?>
							<a href="javascript:lacakPaket('<?= $r->orderid ?>')" class="btn btn-info btn-xs"><i class="fas fa-pallet"></i> Lacak</a>
							<a href="javascript:orderselesai('<?= $r->id ?>')" class="btn btn-primary btn-xs"><i class="fas fa-check"></i> Selesai</a>
						<?php } ?>
					</td>
				</tr>
		<?php
				$no++;
			}
		} else {
			echo "<tr><td colspan=6 class='text-center text-danger'>Belum ada pesanan</td></tr>";
		}
		?>
	</table>

	<?= $this->setting->createPagination($rows, $page, $perpage, "loadDikirim"); ?>
</div>

<script type="text/javascript">
	$(function() {
		$("#simpan").on("submit", function(e) {
			e.preventDefault();
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
			$.post("<?= site_url("api/v1/order/inputresi") ?>", datar, function(msg) {
				var data = eval("(" + msg + ")");
				updateToken(data.token);
				$("#modal").modal("hide");
				if (data.success == true) {
					swal.fire("Berhasil", "Resi telah disimpan", "success").then((val) => {
						loadDikirim(1);
					});
				} else {
					swal.fire("Gagal", "Terjadi kesalahan saat menyimpan data, coba ulangi beberapa saat lagi", "error");
				}
			});
		});
	});

	function inputResi(id) {
		$("#theid").val(id);
		$("#modal").modal();
	}
</script>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title"><i class="fas fa-shipping-fast"></i> Input Nomer Resi</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?= form_open('', 'id="simpan"'); ?>
			<input type="hidden" id="theid" name="theid" value="0" />
			<div class="modal-body">
				<div class="form-group">
					<label>Masukkan Nomer Resi</label>
					<input type="text" class="form-control" name="resi" required />
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="submit" class="btn btn-success">Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
			</div>
			</form>
		</div>
	</div>
</div>