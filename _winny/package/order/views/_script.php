<script>
	$(function() {
        $("#cekout").on("submit", function(e) {
            e.preventDefault();
        });
	});
	// format uang
	function formUang(data) {
		return data.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
	}

    // CEK VOUCHER
    $(".voucher-item").on("click", function() {
        var kode = $(this).data("kode");
        $("#kodevoucher").val(kode);
        setTimeout(cekVoucher(), 1000);
    });
    $("#kodevoucher").change(function() {
        $("#diskon").val(0);
        $("#diskonshow").html(0);
        hitungOngkir();
        $(".vouchergagal").hide();
        $(".vouchersukses").hide();
    });
    function cekVoucher() {
        if ($("#kodevoucher").val() != "") {
            $.post("<?= site_url("order/voucher") ?>", {
                    "kode": $("#kodevoucher").val(),
                    "harga": $("#total").val(),
                    [$("#names").val()]: $("#tokens").val(),
                    "ongkir": $("#ongkir").val()
                },
                function(msg) {
                    var data = eval("(" + msg + ")");
                    $("#tokens").val(data.token);
                    if (data.success == true) {
                        var total = parseFloat($("#total").val()) - data.diskon;
                        $("#diskon").val(data.diskon);
                        $("#diskonshow").html(formUang(data.diskon));
                        $("#totalsemuashow").html(formUang(total));
                        $("#transfer").html(formUang(total));
                        $(".vouchergagal").hide();
                        $(".vouchersukses").show();
                    } else {
                        $("#diskon").val(0);
                        $("#diskonshow").html(0);
                        $(".vouchergagal").show();
                        $(".vouchersukses").hide();
                    }
                });
        } else {
            swal.fire("Masukkan Kode Voucher!", "masukkan kode voucher terlebih dahulu lalu klik tombol cek voucher",
                "warning");
        }
    }

    // DROPSHIP
    $("#nodrop").click(function() {
        $("#yesdrop").removeClass("btn-colorbutton");
        $("#yesdrop").addClass("btn-outline-colorbutton");
        $(this).removeClass("btn-outline-colorbutton");
        $(this).addClass("btn-colorbutton");
        $(".fa", this).show()
        $("#yesdrop .fa").hide();
        $("#dropform").hide();
        $("#dropform input").val("");
        $("#dropform input").prop("required", false);
    });
    $("#yesdrop").click(function() {
        $("#nodrop").removeClass("btn-colorbutton");
        $("#nodrop").addClass("btn-outline-colorbutton");
        $(this).removeClass("btn-outline-colorbutton");
        $(this).addClass("btn-colorbutton");
        $("#dropform").show();
        $(".fa", this).show()
        $("#nodrop .fa").hide();
        $("#dropform input").prop("required", true);
    });

    // HITUNG ONGKIR
    function hitungOngkir() {
        if (($("#idalamat").val() != "" && $("#idalamat").val() != "0") || $("#tujuan").val() > 0) {
            var tujuan = $("#tujuan").val();
            var kurir = $(".kurir").val();
            var service = $(".paket").val();
            var hargapaket = $("#hargapaket").val();
            var berat = $("#berat").val();
            var dari = $("#dari").val();
            if (kurir != "" && service != "") {
                var datar = {
                    "berat": berat,
                    "dari": dari,
                    "tujuan": tujuan,
                    "kurir": kurir,
                    "service": service,
                    "hargapaket": hargapaket,
                    [$("#names").val()]: $("#tokens").val()
                };
                $.post("<?php echo site_url("shipping/check"); ?>", datar, function(msg) {
                    var data = eval("(" + msg + ")");
                    $("#tokens").val(data.token);
                    if (data.success == true) {
                        $("#ongkir").val(data.harga).trigger("change");
                        if (data.harga == 0 && $(".kurir").val() != "cod") {
                            errorKurir();
                        }
                    } else {
                        $("#ongkir").val(0).trigger("change");
                        errorKurir();
                    }
                    calculateOngkir();
                });
            }
        } else {
            Swal.fire("Penting!", "Lengkapi data diri dan alamat terlebih dahulu", "error");
        }
        function errorKurir() {
            $("#error-kurir").show();
        }
    }
    function calculateOngkir() {
        var sum = 0;
        var sumi = true;
        $(".ongkir").each(function() {
            sum += parseFloat($(this).val());
            if ($(this).val() > 0 && sumi == true) {
                sumi = true;
            } else {
                if ($(".kurir").val() == "gosend" || $(".kurir").val() == "lain") {
                    sumi == true;
                } else {
                    sumi = false;
                }
            }
        });

		//RESET PEMBAYARAN
        if (sumi == false) {
            $(".pembayaran").hide();
            $("#error-bayar").show();
        } else {
            $(".pembayaran").show();
            $("#error-bayar").hide();
        }

        <?php if (isset($preorder) && $preorder) : ?>
            // var totalsemuashow = sum + parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val());
            var totalsemuashow = sum + parseFloat($("#subtotal").val());
            $("#transfer").html("Rp "+formUang(totalsemuashow));
        <?php else : ?>
            var totalsemuashow = sum + parseFloat($("#subtotal").val());
        <?php endif; ?>

        $("#ongkirshow").html(formUang(sum));
        $('.ongkir').val(sum);
        $("#total").val(totalsemuashow);
        $("#totalsemuashow").html(formUang(totalsemuashow));
        
		$("#saldo").html("Rp 0");
		$("#saldopotong").val('0');
		$("#metode option").prop("selected",false);
		$("#metode option[value=1]").prop("selected",true).trigger("change");
    }
    function resetOngkir() {
        <?php if (isset($preorder) && $preorder) : ?>
            // var totalsemuashow = parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val());
            var totalsemuashow = parseFloat($("#subtotal").val());
            totalsemuashow = formUang(totalsemuashow);
            $("#transfer").html("Rp "+formUang($("#subtotal").val()));
        <?php else : ?>
            var totalsemuashow = '0';
        <?php endif; ?>
        $("#total").val($("#subtotal").val());
        $('#totalsemuashow').html(totalsemuashow);
        $('#ongkirshow').html('0');
        $('.ongkir').val('0');
        
		//RESET PEMBAYARAN
		$("#error-bayar").show();
		$(".pembayaran").hide();

        $("#saldo").html("Rp 0");
        $("#saldopotong").val('0');
        $("#metode option").prop("selected",false);
        $("#metode option[value=1]").prop("selected",true).trigger("change");
    }

    // KURIR PENGIRIMAN
    $(".ongkir").change(function() {
        var ongkir = $(this).val();
        var harga = $("#total").val();
        var total = Number(harga) + Number(ongkir);
    });
    $(".kurir").change(function () {
        $("#info-paket").addClass('mb-3').html("<i class='fas fa-spin fa-spinner'></i> Loading...");
        var me = $(this).val();
        var datar = {
            "kurir": $(this).val(),
            "berat": $("#berat").val(),
            "tujuan": $("#tujuan").val(),
            [$("#names").val()]: $("#tokens").val()
        };
        $.post("<?php echo site_url("shipping"); ?>", datar, function (msg) {
            var data = eval("(" + msg + ")");
            $("#tokens").val(data.token);
            $("#error-kurir").hide();
            $(".ongkir").val("0");
            $("#ongkirshow").html("0");
            resetOngkir();

            if (me == 'gosend' || me == 'lain') {
                $("#paketform").hide();
                $(".paket").hide();
                $("#info-paket").html("<div class='alert alert-info'>Pembayaran Ongkir dilakukan oleh Pembeli, Silahkan Konfirmasi Transaksi Pembelian ke Admin</div>")
                calculateOngkir();
            } else {
                $("#info-paket").removeClass().html('')
                $("#paketform").show()
                $(".paket").show();
                $(".paket").html(data.html)
            }
        });
    });
    $(".paket").change(function () {
        var me = $(this).find(':selected').data('harga');
        $("#hargapaket").val(me);
        $("#error-kurir").hide();
        hitungOngkir();
    });

    // ALAMAT PENGIRIMAN
    $("#idalamat").change(function() {
        var idalamat = $(this).val();
        var tujuan = $("#alamat_" + idalamat).data('tujuan');
        $("#tujuan").val(tujuan);
        $(".kurir").val('');

        $(".alamat").hide();
        if ($(this).val() == "") {
            resetOngkir();
            $(".tambahalamat").hide();
            $(".tambahalamat input,.tambahalamat textarea").prop("required", false);
        } else if ($(this).val() == 0) {
            resetOngkir();
            $(".tambahalamat").show();
            $(".tambahalamat input,.tambahalamat textarea").prop("required", true);
            if ($("#kab").val() != "") {
                $("#tujuan").val($("#kab").val());
                hitungOngkir();
            } else {
                resetOngkir();
            }
        } else if ($(this).val() > 0) {
            $("#alamat_" + idalamat).show();
            $(".tambahalamat").hide();
            $(".tambahalamat input,.tambahalamat textarea").prop("required", false);

            hitungOngkir();
        }
    });
    $("#prov").change(function() {
        $("#kab").html("<option value=''>Loading...</option>");
        // $("#kec").html("<option value=''>Kecamatan</option>");
        resetOngkir();
        $.post("<?php echo site_url("regional/kabupaten"); ?>", {
            "id": $(this).val(),
            [$("#names").val()]: $("#tokens").val()
        },
        function(msg) {
            var data = eval("(" + msg + ")");
            $("#tokens").val(data.token);
            $("#kab").html(data.html);
        });
    });
    $("#kab").change(function() {
        var data = $(this).find(":selected").val();
        var postal_code = $(this).find(":selected").data('kodepos');
        $("#tujuan").val(data);
        $("#kodepos").val(postal_code);
        $(".kurir").val('');
        $(".paket").val('');
        hitungOngkir();
    });

    // METODE Bayar
    $("#metode").change(function() {
        var saldo = parseFloat($("#saldoval").val());
        var total = parseFloat($("#total").val());

        if ($(this).val() == 2) {
            if (saldo >= total) {
                $("#saldopotong").val(total);
                $("#saldo").html("Rp " + formUang(total));
                $("#transfer").html("Rp 0");
            } else {
                var selisih = total - saldo;
                $("#saldopotong").val(saldo);
                $("#saldo").html("Rp " + formUang(saldo));
                $("#transfer").html("Rp " + formUang(selisih));
            }
        } else {
            $("#saldopotong").val(0);
            $("#saldo").html("Rp 0");
            $("#transfer").html("Rp " + formUang(total));
        }
    });
    function checkoutNow() {
        $(".pembayaran").hide();
        $("#proses").show();

        $(".idproduks").each(function() {
            var id = $(this).val();
            var nama = $("#namaproduks_" + id).val();
            var kategori = $("#kategoriproduks_" + id).val();
            var total = $("#totalproduks_" + id).val();
            fbq('track', 'Purchase', {
                content_ids: id,
                content_type: kategori,
                content_name: nama,
                currency: "IDR",
                value: total
            });
        });

        $.post("<?php echo site_url("order/home/save"); ?>", $("#cekout").serialize(), function(msg) {
            var data = eval("(" + msg + ")");
            $("#tokens").val(data.token);
            if (data.success == true) {
                window.location.href = data.url;
            } else {
                $(".pembayaran").show();
                $("#proses").hide();
            }
        });
    }
</script>