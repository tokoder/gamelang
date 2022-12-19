<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

if ($user->level == 1) {
    $level = "Member Silver";
} else if ($user->level == 2) {
    $level = "Reseller";
} else if ($user->level == 3) {
    $level = "Agen";
} else {
    $level = "Distributor";
}
?>

<!-- Page Title
============================================= -->
<section id="page-title">
    <div class="container clearfix">
        <h1><?= $profil->nama; ?></h1>
        <?= $level; ?>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="tabs clearfix" id="tab-10">

                <div class="tabbable-responsive">
                    <div class="tabbable">
                        <ul class="tab-nav nav nav-tabs clearfix">
                            <li><a onclick="backAccount()" class="bg-info text-white button m-0"><i class="icon-home2 mr-0"></i></a></li>
                            <li><a href="#belumbayar" class="navlink"><i class="fas fa-wallet"></i> Belum Bayar</a></li>
                            <li><a href="#dikemas" class="navlink"><i class="fas fa-box"></i> Dikemas</a></li>
                            <li><a href="#dikirim" class="navlink"><i class="fas fa-shipping-fast"></i> Dikirim</a></li>
                            <li><a href="#selesai" class="navlink"><i class="fas fa-star"></i> Selesai</a></li>
                            <li><a href="#batal" class="navlink"><i class="fas fa-times"></i> Dibatalkan</a></li>
                            <li><a href="#preorder" class="navlink"><i class="fas fa-hourglass-half"></i> Preorder</a></li>
                        </ul>
                    </div>
                </div>

                <div class="tab-container">
                    <!-- BELUM BAYAR -->
                    <div class="tab-content clearfix" id="belumbayar"></div>

                    <!-- DIKEMAS -->
                    <div class="tab-content clearfix" id="dikemas" style="display:none;"></div>

                    <!-- DIKIRIM -->
                    <div class="tab-content clearfix" id="dikirim" style="display:none;"></div>

                    <!-- SELESAI -->
                    <div class="tab-content clearfix" id="selesai" style="display:none;"></div>

                    <!-- BATAL -->
                    <div class="tab-content clearfix" id="batal" style="display:none;"></div>

                    <!-- PRE ORDER -->
                    <div class="tab-content clearfix" id="preorder" style="display:none;"></div>

                </div>

            </div>
        </div>
    </div>
</section>

<!-- Modal1 -->
<div class="modal fade" id="statusmodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Status Pembayaran</h5>
                <button type="button" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-danger fs-24 p-all-2"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="status" class="col-12"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal1 -->
<div class="modal fade" id="konfirmasimodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p>Upload Bukti Transfer <span class="fs-14">(.jpg, .png, .pdf)</span></p>
                <?=form_open_multipart(site_url("account/konfirmasi"), 'id="upload" class="row mb-0"');?>
                    <input name="idbayar" type="hidden" id="bayar" value="0"/>
                    <div class="col-md-12 mb-3">
                        <input type="file" name="bukti" class="form-control" accept="image/*" />
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-chevron-circle-up"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        <?php
        if (isset($_GET["konfirmasi"])) {
            $datar = [
                "ipaymu" => "",
                "ipaymu_link" => "",
                "ipaymu_trx" => "",
                "ipaymu_tipe" => "",
                "ipaymu_channel" => "",
                "ipaymu_nama" => "",
                "ipaymu_kode" => "",
                "midtrans_id" => ""
            ];
            $this->db->where("id", $_GET["konfirmasi"]);
            $this->db->update("invoice", $datar);
            $this->setting->notiftransfer($_GET["konfirmasi"]);
            echo "konfirmasi(" . $_GET["konfirmasi"] . ")";
        }
        ?>

        $("#belumbayar").load("<?php echo site_url("account/order/status?status=belumbayar"); ?>");
        $(".navlink").each(function() {
            var tab = $(this).attr("href");
            var res = tab.replace("#", "");

            $(this).click(function() {
                $(".tab-content").hide();
                $(tab).show();
                $(tab).html("<div class='m-lr-auto m-t-50 text-center p-tb-10'><h4><div class='spinner-border color1' role='status'><span class='sr-only'>Loading...</span></div></h4></div>");
                $(tab).load("<?php echo site_url("account/order/status?status="); ?>" + res);
            });
        });

        $("#upload").on("submit", function(e) {
            $("#upload button").hide();
            $("#upload").append("<h5 class=''><div class='spinner-border color1' role='status'><span class='sr-only'>Loading...</span></div> Mengunggah...</h5>");
        });
    });

    function cekMidtrans(bayar) {
        $('#statusmodal').modal();
        $("#status").load("<?= site_url("assync/cekmidtrans") ?>?bayar=" + bayar);
    }

    function konfirmasi(bayar) {
        $('#konfirmasimodal').modal();
        $("#bayar").val(bayar);
    }

    function terimaPesanan(trx) {
        Swal.fire({
                title: "Anda yakin?",
                text: "pesanan akan di selesaikan dan dana akan diteruskan kepada penjual.",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Oke",
                denyButtonText: "Batal"
            })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.post("<?php echo site_url("order/status/terima"); ?>", {
                        "pesanan": trx,
                        [$("#names").val()]: $("#tokens").val()
                    }, function(msg) {
                        var data = eval("(" + msg + ")");
                        updateToken(data.token);
                        if (data.success == true) {
                            refreshDikirim(1)
                            $(".selesai").trigger("click");
                        } else {
                            Swal.fire("Gagal!", "Gagal menyelesaikan pesanan, coba ulangi beberapa saat lagi", "error");
                        }
                    });
                }
            });
    }

    function ajukanbatal(trx) {
        Swal.fire({
                title: "Anda yakin?",
                text: "pesanan akan dibatalkan dan apabila penjual telah menyetujui maka pembayaran akan dikembalikan ke saldo Anda.",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Oke",
                denyButtonText: "Batal"
            })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.post("<?php echo site_url("order/status/batalkan"); ?>", {
                        "pesanan": trx,
                        [$("#names").val()]: $("#tokens").val()
                    }, function(msg) {
                        var data = eval("(" + msg + ")");
                        updateToken(data.token);
                        if (data.success == true) {
                            refreshBatal(1);
                            $(".batal").trigger("click");
                        } else {
                            Swal.fire("Gagal!", "Gagal mengajukan pembatalan pesanan, coba ulangi beberapa saat lagi", "error");
                        }
                    });
                }
            });
    }

    function batal(bayar) {
        Swal.fire({
                title: "Anda yakin?",
                text: "pesanan akan dibatalkan.",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Oke",
                denyButtonText: "Batal"
            })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.post("<?php echo site_url("account/order/batal"); ?>", {
                        "pesanan": bayar,
                        [$("#names").val()]: $("#tokens").val()
                    }, function(msg) {
                        var data = eval("(" + msg + ")");
                        updateToken(data.token);
                        if (data.success == true) {
                            refreshBatal(1);
                            $(".batal").trigger("click");
                        } else {
                            Swal.fire("Gagal!", "Gagal membatalkan pesanan, doba ulangi beberapa saat lagi", "error");
                        }
                    });
                }
            });
    }

    function perpanjang(bayar) {
        Swal.fire({
                title: "Anda yakin?",
                text: "Batas waktu pengemasan penjual akan diperpanjang 2 hari.",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: "Oke",
                denyButtonText: "Batal"
            })
            .then((willDelete) => {
                if (willDelete.isConfirmed) {
                    $.post("<?php echo site_url("order/status/perpanjang"); ?>", {
                        "pesanan": bayar,
                        [$("#names").val()]: $("#tokens").val()
                    }, function(msg) {
                        var data = eval("(" + msg + ")");
                        updateToken(data.token);
                        if (data.success == true) {
                            refreshBatal(1);
                            $(".dikemas").trigger("click");
                        } else {
                            Swal.fire("Gagal!", "Gagal membatalkan pesanan, doba ulangi beberapa saat lagi", "error");
                        }
                    });
                }
            });
    }

    function refreshBelumbayar(page) {
        $('.tabs').tabs({
            active: 0
        });
        $("#belumbayar").html("<div class='m-lr-auto text-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#belumbayar").load("<?php echo site_url("account/order/status?status=belumbayar&page="); ?>" + page);
    }

    function refreshBatal(page) {
        $('.tabs').tabs({
            active: 4
        });
        $("#batal").html("<div class='m-lr-auto txt-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#batal").load("<?php echo site_url("account/order/status?status=batal&page="); ?>" + page);
    }

    function refreshDikemas(page) {
        $('.tabs').tabs({
            active: 1
        });
        $("#dikemas").html("<div class='m-lr-auto txt-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#dikemas").load("<?php echo site_url("account/order/status?status=dikemas&page="); ?>" + page);
    }

    function refreshDikirim(page) {
        $('.tabs').tabs({
            active: 2
        });
        $("#dikirim").html("<div class='m-lr-auto txt-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#dikirim").load("<?php echo site_url("account/order/status?status=dikirim&page="); ?>" + page);
    }

    function refreshSelesai(page) {
        $('.tabs').tabs({
            active: 3
        });
        $("#selesai").html("<div class='m-lr-auto txt-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#selesai").load("<?php echo site_url("account/order/status?status=selesai&page="); ?>" + page);
    }

    function refreshPO(page) {
        $('.tabs').tabs({
            active: 5
        });
        $("#preorder").html("<div class='m-lr-auto txt-center p-tb-40'><h4><i class='fas fa-spin fa-compact-disc text-success'></i> loading...</h4></div>");
        $("#preorder").load("<?php echo site_url("account/order/status?status=po&page="); ?>" + page);
    }

    function backAccount() {
        window.location.href = "<?= site_url("account") ?>";
    }
</script>