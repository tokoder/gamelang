<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="tabs clearfix" id="tabs-profile">

                        <div class="tabbable-responsive">
                            <div class="tabbable">
                                <ul class="tab-nav nav nav-tabs clearfix">
                                    <!-- <li><a href="#tab-saldo" class="navlink"><i class="fas fa-wallet"></i> Saldo Saya</a></li> -->
                                    <!-- <li><a href="#tab-order" class="navlink"><i class="fas fa-wallet"></i> Transaksi</a></li> -->
                                    <!-- <li><a href="#tab-rekening" class="navlink"><i class="far fa-credit-card"></i> Rekening</a></li> -->
                                    <li><a href="#tab-alamat" class="navlink"><i class="fas fa-house-user"></i> Alamat</a></li>
                                    <li><a href="#tab-profil" class="navlink"><i class="fas fa-users-cog"></i> Pengaturan</a></li>
                                    <li><a onclick="trackOrder()" class="bg-info text-white button m-0"><i class="fas fa-shipping-fast"></i> Lacak Status Pesanan</a></li>
                                    <li><a onclick="signoutNow()" class="bg-danger text-white button m-0"><i class="fas fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="tab-container">
                            <!-- Alamat -->
                            <div class="tab-content clearfix" id="tab-alamat"></div>

                            <!-- Profil -->
                            <div class="tab-content clearfix" id="tab-profil" style="display:none;"></div>
                            
                            <!-- Saldo -->
                            <div class="tab-content clearfix" id="tab-saldo" style="display:none;"></div>
                            
                            <!-- Order -->
                            <div class="tab-content clearfix" id="tab-order" style="display:none;"></div>

                            <!-- Rekening -->
                            <div class="tab-content clearfix" id="tab-rekening" style="display:none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
$(function() {
    $("#tab-alamat").html("<div class='m-lr-auto m-t-50 text-center p-tb-10'><h4><div class='spinner-border color1' role='status'><span class='sr-only'>Loading...</span></div></h4></div>");
    $("#tab-alamat").load("<?php echo site_url("account/setting?setting=alamat"); ?>");
    $(".navlink").each(function() {
        var tab = $(this).attr("href");
        var res = tab.replace("#tab-", "");

        $(this).click(function() {
            $(".tab-content").hide();
            $(tab).show();
            $(tab).html("<div class='m-lr-auto m-t-50 text-center p-tb-10'><h4><div class='spinner-border color1' role='status'><span class='sr-only'>Loading...</span></div></h4></div>");
            $(tab).load("<?php echo site_url("account/setting?setting="); ?>" + res);
        });
    });
});

// Sign Not Now
function signoutNow() {
    Swal.fire({
            title: "Logout",
            text: "yakin akan logout dari akun anda?",
            icon: "warning",
            showDenyButton: true,
            confirmButtonText: "Oke",
            denyButtonText: "Batal"
        })
        .then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.href = "<?= site_url("auth/signout") ?>";
            }
        });
}
function trackOrder() {
    window.location.href = "<?= site_url("account/order") ?>";
}
</script>