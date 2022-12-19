<script type="text/javascript">
$(function(){
    $("#login").on("submit",function(e){
        e.preventDefault();
        var btn = $(".btn-success").html();
        $(".btn-success").html("<i class='la la-spin la-spinner'></i> Tunggu Sebentar...");
        $.post("<?php echo site_url("admin/auth/login"); ?>",$(this).serialize(),function(msg){
            var dt = eval("("+msg+")");
            $(".tokens").val(dt.token);
            $(".btn-success").html(btn);
            if(dt.success == true){
                swal.fire("Berhasil!","selamat datang kembali "+dt.name,"success").then(function(){
                    window.location.href = "<?=site_url("admin");?>";
                });
            }else{
                swal.fire("Gagal!","gagal masuk, cek kembali username & password anda","warning");
            }
        });
    });
});
</script>