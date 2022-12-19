<?php
    if($id != 0){
        $this->db->where("id",intval($id));
        $db = $this->db->get("@promo");
        foreach($db->result() as $r){
        }
    }
?>
<?= form_open_multipart('', 'id="saveform"'); ?>
    <input type="hidden" name="id" value="<?=intval($id)?>" />
    <div class="row">
        <div class="col-md-12">
            <a class="float-right btn btn-danger" href="javascript:history.back()"><i class="la la-arrow-left"></i> Kembali</a>
            <?php if($id == 0){ ?>
			<h4 class="page-title">Tambah Promo Slider</h4>
			<?php }else{ ?>
			<h4 class="page-title">Edit Promo Slider</h4>
			<?php } ?>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Detail Promo</div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="caption" class="form-control col-md-10" value="<?php echo (isset($r->caption)) ? $r->caption : ""; ?>" />
                    </div>
                    <div class="form-group">
                        <label>Jenis / Lokasi Promo</label>
                        <select class="form-control col-md-6" name="jenis" required >
							<option value="0"<?php echo (isset($r->jenis) AND $r->jenis == 0) ? " selected" : ""; ?>>Pilih Jenis/Lokasi Promo</option>
							<option value="1"<?php echo (isset($r->jenis) AND $r->jenis == 1) ? " selected" : ""; ?>>SLIDER</option>
							<option value="2"<?php echo (isset($r->jenis) AND $r->jenis == 2) ? " selected" : ""; ?>>SPACE IKLAN</option>
						</select>
                    </div>
                    <div class="form-group">
                        <label>Link Promo</label>
                        <input type="text" name="link" class="form-control" value="<?php echo (isset($r->link)) ? $r->link : ""; ?>" />
                    </div>
                    <div class="form-group row m-lr-0">
                        <label class="col-12 p-lr-0">Tanggal Tayang Promo</label>
						<div class="col-md-6 p-l-0 p-r-5">
							<input type="text" name="tgl" class="form-control m-b-10 dtp" value="<?php echo (isset($r->tgl)) ? $r->tgl : ""; ?>" />
						</div>
						<div class="col-md-6 p-l-5 p-r-0">
							<input type="text" name="tgl_selesai" class="form-control m-b-10 dtp" value="<?php echo (isset($r->tgl_selesai)) ? $r->tgl_selesai : ""; ?>" />
						</div>
                    </div>
                    <div class="form-group">
                        <label>Status Promo</label>
                        <select class="form-control col-md-6" name="status" required >
							<option value="1"<?php echo (isset($r->status) AND $r->status == 1) ? " selected" : ""; ?>>AKTIF</option>
							<option value="0"<?php echo (isset($r->status) AND $r->status == 0) ? " selected" : ""; ?>>NON AKTIF</option>
						</select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Foto Display</div>
                </div>
                <div class="card-body">
                    <?php if(!isset($r->gambar)){ ?>
                        <input type='file' accept="image/*" name="gambar" id="imgInp" />
                        <a href="javascript:void(0)" class="btn btn-secondary" onclick="selectIMG()"><i class="la la-image"></i> Pilih Foto</a>
                        <div class="divider"></div>
                        <div class="imgInpPreview">
                            <div class="text">Pilih foto</div>
                            <img id="blah" class="imgpreview" src="#" alt="gambar" />
                            <div  class="delete">
                                <a href="javascript:void(0)" onclick="clearIMG()"><i class="la la-times"></i> hapus</a>
                            </div>
                        </div>
                    <?php 
                        }else{
                            echo "<img src='".base_url('uploads/promo/'.$r->gambar)."' class='imgPreview' />";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <button type="submit" class="btn btn-primary submit"><i class="la la-check-circle"></i> Simpan Promo</button>
        <button type="reset" class="btn btn-warning"><i class="la la-refresh"></i> Reset</button>
    </div>
</form>

<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    function selectIMG(){
        $("#imgInp").trigger("click");
    }
    function clearIMG(){
        $("#imgInp").val(null).trigger("change");
    }

    $(function(){
		$(".dtp").datetimepicker({
			format: "YYYY-MM-DD HH:mm:ss"
		});
		$("#saveform").on("submit",function(){
			var btn = $(".submit").hmtl();
			$(".submit").hmtl("<i class='fas fa-spin fa-spinner'></i> Menyimpan...");
			$(".submit").prop("disabled",true);
		});
		
        $("#imgInp").change(function() {
            if($(this).val() != ""){
                readURL(this);
                $("#blah").show();
                $(".delete").show();
                $(".text").hide();
            }else{
                $("#blah").hide();
                $(".delete").hide();
                $(".text").show();
            }
        });
    });
</script>