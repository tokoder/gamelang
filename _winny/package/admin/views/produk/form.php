<a href="javascript:history.back()" class="btn btn-danger float-right"><i class="la la-times"></i> Batal</a>
<?php if ($id == 0) { ?>
	<h4 class="page-title">Tambah Produk Baru</h4>
<?php } else { ?>
	<h4 class="page-title">Edit Produk</h4>
<?php } ?>

<?php
if ($id != 0) {
	$data = $this->setting->getProduk($id, "semua");
	if ($data == null) {
		redirect("admin/produk");
		exit;
	} else {
		$this->db->where("idproduk", $id);
		$up = $this->db->get("produk_upload");

		$_SESSION["uploadedPhotos"] = $up->num_rows();
		foreach ($up->result() as $ra) {
			$_SESSION["fotoProduk"][] = $ra->id;
		}
	}
	$url = site_url("api/v1/produk/update");
} else {
	$url = site_url("api/v1/produk/tambah");
}
?>

<div class="row m-b-60">
	<div class="col-md-6">
		<?= form_open('', 'id="produk"'); ?>
		<input type="hidden" name="id" value="<?= $id ?>" />
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					Nama & Kategori Produk
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label>Nama Produk</label>
					<input type="text" class="form-control" name="nama" value="<?php echo ($id != 0) ? $data->nama : ""; ?>" required />
				</div>
				<div class="form-group">
					<label>Kode Produk</label>
					<input type="text" class="form-control" name="kode" value="<?php echo ($id != 0) ? $data->kode : date("dHis"); ?>" required />
				</div>
				<div class="form-group">
					<button type="button" id="nopo" onclick="setPO(0)" class="btn btn-primary"><i class="fa fa-check-circle"></i> Stok Ready</button>
					<button type="button" id="po" onclick="setPO(1)" class="btn btn-outline-primary"><i class="fa fa-check-circle" style="display:none;"></i> Pre Order</button>
					<input type="hidden" class="form-control" id="preorder" name="preorder_id" value="<?php echo ($id != 0) ? $data->preorder_id : "0"; ?>" required />
				</div>
				<div class="form-group tglpo" style="display:none;">
					<label>Tanggal Tersedia</label>
					<input type="text" class="form-control col-6 dtp" name="tglpo" value="<?php echo ($id != 0) ? $data->tglpo : ""; ?>" />
				</div>
				<div class="form-group">
					<label>Kategori</label>
					<select class="form-control col-md-6" name="idcat" required>
						<option value="">- Pilih Kategori -</option>
						<?php
						$kat = $this->db->get("@kategori");
						foreach ($kat->result() as $r) {
							$selec = ($id != 0 and $data->idcat == $r->id) ? "selected" : "";
							echo "<option value='" . $r->id . "' $selec>" . $r->nama . "</option>";
						}
						?>
					</select>
				</div>
				<div class="row p-lr-15">
					<div class="form-group col-md-6">
						<label>Nama Variasi</label>
						<input type="text" class="form-control" placeholder="contoh: Warna" name="variasi" value="<?php echo ($id != 0) ? $data->variasi : ""; ?>" required />
					</div>
					<div class="form-group col-md-6">
						<label>Nama Sub Variasi</label>
						<input type="text" class="form-control" placeholder="contoh: Size" name="subvariasi" value="<?php echo ($id != 0) ? $data->subvariasi : ""; ?>" required />
					</div>
					<div class="form-group col-md-12">
						<small><i class="text-danger">Apabila tidak ada Variasi, isi dengan strip (-)</i></small>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					Detail Harga & Stok
				</div>
			</div>
			<div class="card-body">
				<div class="form-group row m-lr-0">
					<label class="col-12 p-lr-0">Minimal Order</label>
					<input type="number" class="form-control col-md-4 col-6" name="minorder" value="<?php echo ($id != 0) ? $data->minorder : 1; ?>" required />
					<small class="col-md-8 col-6"><i>jumlah produk minimal setiap order</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Stok Barang</label>
					<input type="number" class="form-control col-md-6" id="stok" name="stok" value="<?php echo ($id != 0) ? $data->stok : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 200</i></small>
				</div>
				<div class="form-group row m-lr-0">
					<label class="col-12 p-lr-0">Harga Diskon (Harga Coret)</label>
					<input type="number" class="form-control col-md-6" name="hargacoret" value="<?php echo ($id != 0) ? $data->hargacoret : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 200000</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Harga Normal</label>
					<input type="number" class="form-control col-md-6" name="harga" value="<?php echo ($id != 0) ? $data->harga : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 200000</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Harga Reseller</label>
					<input type="number" class="form-control col-md-6" name="hargareseller" value="<?php echo ($id != 0) ? $data->hargareseller : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 250000</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Harga Agen</label>
					<input type="number" class="form-control col-md-6" name="hargaagen" value="<?php echo ($id != 0) ? $data->hargaagen : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 260000</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Harga Agen Premium</label>
					<input type="number" class="form-control col-md-6" name="hargaagensp" value="<?php echo ($id != 0) ? $data->hargaagensp : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 290000</i></small>
				</div>
				<div class="form-group row m-lr-0 novariasi">
					<label class="col-12 p-lr-0">Harga Distributor</label>
					<input type="number" class="form-control col-md-6" name="hargadistri" value="<?php echo ($id != 0) ? $data->hargadistri : 0; ?>" required />
					<small class="col-md-6"><i>hanya masukkan angka saja. cth: 220000</i></small>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header">
				<div class="card-title">
					Deskripsi Produk
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label>Berat Produk (gram)</label>
					<input type="number" class="form-control col-md-4 col-6" name="berat" value="<?php echo ($id != 0) ? $data->berat : 250; ?>" required />
					<small>hanya isi dengan angka, misal berat 1kg maka isi: 1000</small>
				</div>
				<!--<div class="form-group" id="stok">
						<label>Stok Produk</label>
						<input type="number" class="form-control col-md-4 col-6" name="stok" value="<?php //echo ($id != 0) ? $data->stok : 0; 
																									?>" required />
						<small>apabila ada variasi produk, maka abaikan form stok atau isi dengan 0</small>
					</div>-->
				<div class="form-group">
					<label>Deskripsi Produk</label>
					<textarea class="form-control" id="editor" name="deskripsi"><?php echo ($id != 0) ? $data->deskripsi : ""; ?></textarea>
				</div>
				<div class="form-group">
					<label>Status Publikasi</label>
					<select class="form-control col-md-6" name="status" required>
						<option value="1">Published</option>
						<option value="0">Draft</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card saveproduk-imp">
			<div class="card-body text-right">
				<button type="submit" class="btn btn-primary"><i class="la la-check-circle"></i> Simpan</button>
				<button type="reset" class="btn btn-warning"><i class="la la-refresh"></i> Reset</button>
				<button type="button" onclick="javascript:history.back()" class="btn btn-danger"><i class="la la-times"></i> Batal</button>
			</div>
		</div>
		</form>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="card-title">Foto Produk</div>
			</div>
			<div class="card-body">
				<div class="m-b-20 overflow-hidden btn-block">
					<div id="foto-produk" class="uploadfoto-result">
					</div>
					<div class="uploadfoto">
						<label class="form-uploadfoto">
							<input type="file" name="fotoProduk" id="fotoUpload" accept="image/x-png,image/gif,image/jpeg"></input>
							<img src="<?php echo base_url("assets/img/add-product.png"); ?>" />
						</label>
						<span id="prosesUpload"></span>
					</div>
				</div>
				<div class="text-danger">
					<small><i>Ukuran file maksimal 2MB, resolusi maksimal 2000 pixel</i></small>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-header row m-lr-0">
				<div class="card-title col-6 p-lr-0" id="stokvariasi">Variasi Stok Produk</div>
				<div class="card-title col-6 p-lr-0 text-right">
					<button type="button" class="btn btn-success btn-sm" onclick="tambahVariasi()"><i class="fas fa-plus"></i> Tambah Stok</button>
				</div>
			</div>
			<div class="card-body">
				<?= form_open('', 'id="variasi"'); ?>
				<?php
				if ($id != 0) {
					$this->db->where("idproduk", $id);
					$vars = $this->db->get("produk_variasi");
					if ($vars->num_rows() != 0) {
						foreach ($vars->result() as $r) {
							?>
							<div class="form-group row m-lr-0 align-items-center m-b-20 bg-light p-all-12" style="border: 1px solid #ccc;border-bottom: 5px solid #ccc;">
								<input type="hidden" name="id[]" value="<?= $r->id ?>" />
								<div class="col-4 p-lr-5">
									<label>Variasi</label>
									<select class="form-control" name="warna[]">
										<option value="0">Variasi</option>
										<?php
										$wr = $this->db->get("produk_variasiwarna");
										foreach ($wr->result() as $rw) {
											$selec = ($rw->id == $r->warna) ? "selected" : "";
											echo "<option value='" . $rw->id . "' " . $selec . ">" . $rw->nama . "</option>";
										}
										?>
									</select>
								</div>
								<div class="col-4 p-lr-5">
									<label>Sub Variasi</label>
									<select class="form-control" name="size[]">
										<option value="0">Sub Variasi</option>
										<?php
										$wr = $this->db->get("produk_variasisize");
										foreach ($wr->result() as $rs) {
											$selec = ($rs->id == $r->size) ? "selected" : "";
											echo "<option value='" . $rs->id . "' " . $selec . ">" . $rs->nama . "</option>";
										}
										?>
									</select>
								</div>
								<div class="col-3 p-lr-5">
									<label>Stok</label>
									<input type="number" class="form-control" name="stok[]" placeholder="stok" value="<?= $r->stok ?>" />
								</div>
								<div class="col-1 p-lr-5"><a href="javascript:void(0)" class="btn btn-sm btn-danger hapusvariasion" data-varid="<?= $r->id ?>"><i class="fas fa-times"></i></a></div>
								<div class="col-12 p-lr-5 m-t-20 m-b-0 text-success">
									<b>Detail Harga</b>
								</div>
								<div class="col-4 p-lr-5 m-t-8">
									<label>Ecer</label>
									<input type="text" class="form-control m-t-5" name="harga[]" value="<?= $r->harga ?>" />
								</div>
								<div class="col-4 p-lr-5 m-t-8">
									<label>Reseller</label>
									<input type="text" class="form-control m-t-5" name="hargareseller[]" value="<?= $r->hargareseller ?>" />
								</div>
								<div class="col-4 p-lr-5 m-t-8">
									<label>Agen</label>
									<input type="text" class="form-control m-t-5" name="hargaagen[]" value="<?= $r->hargaagen ?>" />
								</div>
								<div class="col-4 p-lr-5 m-t-8">
									<label>Agen Premium</label>
									<input type="text" class="form-control m-t-5" name="hargaagensp[]" value="<?= $r->hargaagensp ?>" />
								</div>
								<div class="col-4 p-lr-5 m-t-8">
									<label>Distributor</label>
									<input type="text" class="form-control m-t-5" name="hargadistri[]" value="<?= $r->hargadistri ?>" />
								</div>
							</div>
							<?php
						}
					} else {
						echo '<i id="belumada">Belum ada variasi produk</i>';
					}
				}
				?>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    if ($('#editor').length > 0) {
        init_tinymce('#editor', 500);
    }

	$(function() {
		$("#preorder").change(function() {
			if ($(this).val() == 1) {
				$("#po").removeClass("btn-outline-primary");
				$("#po").addClass("btn-primary");
				$("#nopo").addClass("btn-outline-primary");
				$("#nopo").removeClass("btn-primary");
				$("#nopo .fa").hide();
				$(".tglpo").show();
				$("#po .fa").show();
				$("#stokvariasi").html("Kuota Stok Variasi PO");
			} else {
				$("#nopo").removeClass("btn-outline-primary");
				$("#nopo").addClass("btn-primary");
				$("#po").addClass("btn-outline-primary");
				$("#po").removeClass("btn-primary");
				$("#nopo .fa").show();
				$("#po .fa").hide();
				$(".tglpo").hide();
				$("#stokvariasi").html("Variasi Stok Produk");
			}
		});
		$("#preorder").trigger("change");

		$("#variasi").on('click', '.hapusvariasi', function() {
			var therem = $(this).parents(".form-group");
			swal.fire({
				title: "Yakin menghapus variasi?",
				text: "variasi akan dihapus, dan tidak dapat dikembalikan lagi",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Batal"
			}).then((val) => {
				if (val.value) {
					therem.remove();
					if (!$("#variasi input").val()) {
						$("#stok").show();
						$("#belumada").show();
						$(".novariasi").show();
					}
				}
			});
		});
		$("#variasi").on('click', '.hapusvariasion', function() {
			var therem = $(this).parents(".form-group");
			var varid = $(this).data("varid");

			swal.fire({
				title: "Yakin menghapus variasi?",
				text: "variasi akan dihapus, dan tidak dapat dikembalikan lagi, termasuk stok juga akan habis",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Batal"
			}).then((val) => {
				if (val.value) {
					$.post("<?= site_url('admin/variasi/hapus') ?>", {
						"theid": varid,
						[$("#names").val()]: $("#tokens").val()
					}, function(e) {
						var data = eval("(" + e + ")");
						updateToken(data.token);
						if (data.success == true) {
							therem.remove();
							if (!$("#variasi input").val()) {
								$("#stok").show();
								$("#belumada").show();
								$(".novariasi").show();
							}
						} else {
							swal.fire("Gagal", "gagal menghapus variasi, coba ulangi beberapa saat lagi", "danger");
						}
					});
				}
			});
			$("#preorder").trigger('change');
		});

		$("#produk").on("submit", function(e) {
			e.preventDefault();
			var suk = $(".saveproduk-imp .btn-primary").html();
			$(".saveproduk-imp .btn-primary").html("menyimpan...");
			$(".saveproduk-imp .btn-primary").prop("disabled", true);
			var datar = $(this).serialize();
			datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();

			$.post("<?= $url ?>", datar, function(msg) {
				var data = eval("(" + msg + ")");
				updateToken(data.token);
				$(".saveproduk-imp .btn-primary").html(suk);
				$(".saveproduk-imp .btn-primary").prop("disabled", false);
				if (data.success == true) {
					var fom = $("#variasi .form-control").val();
					if (typeof(fom) != "undefined" && fom !== null) {
						var datars = $("#variasi").serialize();
						datars = datars + "&" + $("#names").val() + "=" + $("#tokens").val();
						$.post("<?= site_url("api/v1/produk/variasi") ?>/" + data.id, datars, function(msg) {
							var datas = eval("(" + msg + ")");
							updateToken(datas.token);
							if (datas.success == true) {
								swal.fire("Selesai", "Data produk telah disimpan", "success").then((val) => {
									window.location.href = "<?= site_url("admin/produk") ?>";
								});
							} else {
								swal.fire("Gagal Variasi", datas.msg, "error");
							}
						});
					} else {
						swal.fire("Selesai", "Data produk telah disimpan", "success").then((val) => {
							window.location.href = "<?= site_url("admin/produk") ?>";
						});
					}
				} else {
					swal.fire("Gagal", data.msg, "error");
				}
			});
		});

		<?php if ($id == 0) { ?>
			hapusFoto("all");
		<?php } ?>


		$("#fotoUpload").change(function() {
			var formData = new FormData();
			formData.append("fotoProduk", $("#fotoUpload").get(0).files[0]);
			formData.append("jenis", 1);
			formData.append("idproduk", <?php echo $id; ?>);
			formData.append($("#names").val(), $("#tokens").val());
			$.ajax({
				url: '<?php echo site_url("api/v1/upload"); ?>',
				type: 'POST',
				contentType: false,
				cache: false,
				processData: false,
				data: formData,
				xhr: function() {
					var jqXHR = null;
					if (window.ActiveXObject) {
						jqXHR = new window.ActiveXObject("Microsoft.XMLHTTP");
					} else {
						jqXHR = new window.XMLHttpRequest();
					}
					jqXHR.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = Math.round((evt.loaded * 100) / evt.total);
							$("#prosesUpload").html("mengunggah: " + percentComplete + "%");
						}
					}, false);
					/*jqXHR.addEventListener( "progress", function ( evt )
					{
					    if ( evt.lengthComputable )
					    {
					        var percentComplete = Math.round( (evt.loaded * 100) / evt.total );
					        //Do something with download progress
					        console.log( 'Downloaded percent', percentComplete );
					    }
					}, false );*/
					return jqXHR;
				},
				success: function(data) {
					var datas = eval("(" + data + ")");
					updateToken(datas.token);
					$("#prosesUpload").html("");
					loadResult();
				}
			});
		});

		$(".dtp").datetimepicker({
			format: "YYYY-MM-DD",
			minDate: "<?= date("Y-m-d") ?>"
		});

		loadResult();
	});

	function setPO(vl) {
		<?php if ($id == 0) { ?>
			$('#preorder').val(vl).trigger('change');
		<?php } else { ?>
			swal.fire({
				title: "GAGAL",
				text: "anda tidak dapat mengubah produk preorder ke ready stok atau sebaliknya",
				type: "warning"
			});
		<?php } ?>
	}

	function loadResult() {
		$("#foto-produk").html("mohon tunggu sebentar...");
		$.post('<?php echo site_url("admin/upload/result/" . $id); ?>', {
			"response": 212,
			[$("#names").val()]: $("#tokens").val()
		}, function(msg) {
			var data = eval("(" + msg + ")");
			updateToken(data.token);
			if (data.success == true) {
				$("#foto-produk").html(data.data);
			}
		});
	}

	function hapusFoto(id) {
		if (id != "all") {
			swal.fire({
				title: "Yakin menghapus foto?",
				text: "data yang sudah dihapus tidak dapat dikembalikan",
				type: "warning",
				showCancelButton: true
			}).then((val) => {
				if (val.value) {
					$.post('<?php echo site_url("api/v1/upload/hapus/"); ?>' + id, {
						"response": 212,
						[$("#names").val()]: $("#tokens").val()
					}, function(msg) {
						var data = eval("(" + msg + ")");
						updateToken(data.token);
						if (data.success == true) {
							loadResult();
						} else {
							swal.fire({
								title: "GAGAL",
								text: "gagal meghapus data",
								type: "error"
							});
						}
					});
				}
			});
		} else {
			$.post('<?php echo site_url("api/v1/upload/hapus/"); ?>' + id, {
				"response": 212,
				[$("#names").val()]: $("#tokens").val()
			}, function(msg) {
				var data = eval("(" + msg + ")");
				updateToken(data.token);
			});
		}
	}

	function jadikanUtama(id) {
		$.post('<?php echo site_url("api/v1/upload/utama/"); ?>' + id, {
			"idproduk": <?php echo $id; ?>,
			[$("#names").val()]: $("#tokens").val()
		}, function(msg) {
			var data = eval("(" + msg + ")");
			updateToken(data.token);
			if (data.success == true) {
				loadResult();
			} else {
				confirm("GAGAL!!!");
			}
		});
	}

	function tambahVariasi() {
		$("#variasi").append($("#variasitambah").html());
		$(".novariasi").hide();
		$("#belumada").hide();
	}

	function cekIsiVariasi() {
		if ($("#variasi").html() == "") {
			$(".novariasi").show();
		}
	}
</script>

<div id="variasitambah" style="display:none;">
	<div class="form-group row m-lr-0 bg-light m-b-20 p-all-12" style="border-bottom: 5px solid #ccc;">
		<input type="hidden" name="id[]" value="0" />
		<div class="col-4 p-lr-5">
			<label>Variasi</label>
			<select class="form-control" name="warna[]">
				<option value="0">Variasi</option>
				<?php
				$wr = $this->db->get("produk_variasiwarna");
				foreach ($wr->result() as $r) {
					echo "<option value='" . $r->id . "'>" . $r->nama . "</option>";
				}
				?>
			</select>
		</div>
		<div class="col-4 p-lr-5">
			<label>Sub Variasi</label>
			<select class="form-control" name="size[]">
				<option value="0">Sub Variasi</option>
				<?php
				$wr = $this->db->get("produk_variasisize");
				foreach ($wr->result() as $r) {
					echo "<option value='" . $r->id . "'>" . $r->nama . "</option>";
				}
				?>
			</select>
		</div>
		<div class="col-3 p-lr-5">
			<label>Stok</label>
			<input type="number" class="form-control" name="stok[]" placeholder="stok" />
		</div>
		<div class="col-1 p-lr-5"><a href="javascript:void(0)" class="btn btn-sm btn-danger hapusvariasi"><i class="fas fa-times"></i></a></div>
		<div class="col-12 p-lr-5 m-t-20 m-b-0 text-success">
			<b>Detail Harga</b>
		</div>
		<div class="col-4 p-lr-5 m-t-8">
			<label>Ecer</label>
			<input type="text" class="form-control" name="harga[]" value="0" />
		</div>
		<div class="col-4 p-lr-5 m-t-8">
			<label>Reseller</label>
			<input type="text" class="form-control" name="hargareseller[]" value="0" />
		</div>
		<div class="col-4 p-lr-5 m-t-8">
			<label>Agen</label>
			<input type="text" class="form-control" name="hargaagen[]" value="0" />
		</div>
		<div class="col-4 p-lr-5 m-t-8">
			<label>Agen Premium</label>
			<input type="text" class="form-control" name="hargaagensp[]" value="0" />
		</div>
		<div class="col-4 p-lr-5 m-t-8">
			<label>Distributor</label>
			<input type="text" class="form-control" name="hargadistri[]" value="0" />
		</div>
	</div>
</div>