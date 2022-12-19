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
<!-- Home -->
<nav class="navbar navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom navbar-bottom" style="background-color: transparent;">
    <div class="container-fluid navbar-nav nav-justified container_nav" id="container_navbar">
        <div class="col-sm-4 nav-item nav-kiri hide">
            <ul class="navbar-nav nav-justified" style="margin-left: 0px">
                <a href="<?= site_url() ?>">
                    <li class="menu-bot coloricon"><i class="material-icons">store</i></li>
                </a>
                <a href="<?= site_url("account/order") ?>">
                    <li class="menu-bot coloricon"><i class="material-icons">local_mall</i></li>
                </a>
            </ul>
        </div>
        <div class="col-sm-4 nav-item">
            <input type="hidden" value="0" id="value_menu">
            <?php if ($this->setting->cekLogin() == true) { ?>
                <label class="notif_badge_menu bg-color1" id="notif_badge_menu"><?= $this->sales_model->getSaleByCart() ?></label>
            <?php } ?>
            <div class="nav-menu menu-in" id="menu"> <i class="fa fa-th"></i></div>
        </div>
        <div class="col-sm-4 nav-item nav-kanan hide">
            <ul class="navbar-nav nav-justified" style="float:right;">
                <a href="<?= site_url("order/cart") ?>">
                    <li class="menu-bot coloricon">
                        <?php if ($this->setting->cekLogin() == true) { ?><label class="notif_badge" style="color:#fff !important"><?= $this->sales_model->getSaleByCart() ?></label>
                        <?php } ?>
                        <i class="material-icons">shopping_cart</i>
                    </li>
                </a>
                <a href="<?= site_url("account") ?>">
                    <li class="menu-bot coloricon">
                        <i class="material-icons">account_circle</i>
                    </li>
                </a>
            </ul>
        </div>
    </div>
</nav>

<!-- Cart -->
<nav class="navbar navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom navbar-bottom animate__animated animate__fadeInUp animate__delay-1s" style="background-color: transparent;">
    <div class="container-fluid navbar-nav bg-color2 p-t-8 p-b-8" style="border-radius: 5px; box-shadow: 0px 0px 15px #555" id="container_navbar">
        <div class="row" style="position: relative; left: 15px; width: 100%;">
            <div class="col-6" style="text-align: center;">
                <h6 class="color1 fs-14 m-t-10">Total: Rp <span class="totalbayar" style="font-weight: bold"><?php echo $this->setting->formUang($totalbayar); ?></span></h6>
            </div>
            <div class="col-6 text-right" style="align-content: right; align-items: right">
                <div style="float: right; text-align: right;">
                    <a href="<?php echo site_url("order/create"); ?>" class="btn btn-sm btn-colorbutton pull-right font-bold" style="height: 40px; line-height:30px; position: relative; right: 0px">
                        Checkout <i class="fa fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Product -->
<?php
if ($this->setting->cekLogin() == true) { ?>
<nav class="navbar navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom navbar-bottom animate__animated animate__fadeInUp animate__delay-1s"
    style="background-color: transparent;">

    <div class="container-fluid navbar-nav nav-justified bg-color2 p-t-8 p-b-8"
        style="border-radius: 5px; box-shadow: 0px 0px 15px #555" id="container_navbar">
        <div class="col-sm-1 nav-item">
            <input type="hidden" value="0" id="value_menu">

            <?php if ($this->setting->cekLogin() == true) { ?><label class="notif_badge_menu bg-color1"
                id="notif_badge_menu" style="left: 5px"><?= $this->sales_model->getSaleByCart() ?></label>
            <?php } ?>
            <a href="<?= site_url('order/cart'); ?>">
                <div class="nav-menu menu-in" id="menu"
                    style="margin-top:-30px; float: left; width: 40px; height:40px; font-size:12px !important; line-height: 57px;">
                    <i class="material-icons">shopping_cart</i> </div>
                <a href="<?= site_url('order/cart'); ?>">

        </div>

        <div class="col-sm-5 nav-item nav-kiri">
            <ul class="navbar-nav nav-justified" style="float: right;">
                <a href="javascript:void(0)" class="btn_beli_wa" data-url="<?= $data->url; ?>">
                    <li class="btn btn-sm btn-beli btn btn-outline-colorbutton"><i
                            class="fab fa-whatsapp"></i> Beli via WA</li>
                </a>

            </ul>
        </div>
        <div class="col-1"></div>
        <div class="col-sm-5 nav-item nav-kanan">
            <ul class="navbar-nav nav-justified" style="float:right;">

                <li class="btn btn-sm btn-beli btn btn-colorbutton"><button type="button"
                        class="animate__animated animate__tada  animate__delay-3s text-light"
                        data-toggle="modal" data-target="#modal_beli" data-url="<?= $data->url; ?>"
                        id="btn_beli_langsung_login"><i class="fa fa-plus"></i> Beli Sekarang</button>
                </li>
            </ul>
        </div>


    </div>
</nav>
<?php } else { ?>
<nav class="navbar navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom navbar-bottom animate__animated animate__fadeInUp animate__delay-1s"
    style="background-color: transparent;">

    <div class="container-fluid navbar-nav nav-justified bg-color2 p-t-8 p-b-8"
        style="border-radius: 5px; box-shadow: 0px 0px 15px #555" id="container_navbar">
        <div class="col-sm-1 nav-item">
            <input type="hidden" value="0" id="value_menu">
            <a href="<?= site_url('order/cart'); ?>">
                <div class="nav-menu menu-in" id="menu"
                    style="margin-top:0px; float: left; width: 40px; height:40px; font-size:12px !important; line-height: 57px;">
                    <i class="material-icons">shopping_cart</i> </div>
            </a>
        </div>

        <div class="col-sm-5 nav-item nav-kiri">
            <ul class="navbar-nav nav-justified" style="float: right;">
                <a href="javascript:void(0)" class="btn_beli_wa" data-url="<?= $data->url; ?>">
                    <li class="btn btn-sm btn-beli btn btn-outline-colorbutton"><i
                            class="fab fa-whatsapp"></i> Beli via WA</li>
                </a>

            </ul>
        </div>
        <div class="col-1"></div>
        <div class="col-sm-5 nav-item nav-kanan">
            <ul class="navbar-nav nav-justified" style="float:right;">

                <li class="btn btn-sm btn-beli btn btn-colorbutton"><button type="button"
                        class="animate__animated animate__tada  animate__delay-3s text-light"
                        data-toggle="modal" data-target="#modal_beli" data-url="<?= $data->url; ?>"
                        id="btn_beli_langsung"><i class="fa fa-plus"></i> Beli Sekarang</button></li>
            </ul>
        </div>


    </div>
</nav>
<?php } ?>

<!-- Checkout -->
<nav class="navbar bg-color2 navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom navbar-bottom" style="z-index: 9999">
    <ul class="navbar-nav nav-justified" style="font-size: 12px;">

        <li class="nav-item" style="text-align: left">
            <h6 class="colortext" style="font-size: 10px">SubTotal: Rp <span class="subtotalbayar" style="font-weight: bold"><?php echo $this->setting->formUang($totalbayar); ?></span></h6>
            <h6 class="colortext" style="font-size: 10px">Ongkir: Rp <span class="ongkirshow" style="font-weight: bold">0</span></h6>
            <h6 class="colortext" style="font-size: 10px">SubTotal: Rp <span class="totalbayar" style="font-weight: bold"><?php echo $this->setting->formUang($totalbayar); ?></span></h6>
        </li>
        <li class="nav-item">
            <div class="button-lanjut-bayar-hide">
                <a href="#" class="btn btn-sm btn-dark btn-block disabled" style="height: 50px; position: relative; right: -50px;">
                    Lanjut Pembayaran <i class="fas fa-chevron-circle-right"></i>
                </a>
            </div>
            <div class="button-lanjut-bayar-show" style="display: none;">
                <a href="javascript:void(0);" onclick="checkoutNow();" class="btn btn-sm btn-colorbutton btn-block button-lanjut-bayar-show" style="height: 50px; position: relative; right: -50px;"> <span id="lanjut_pembayaran">
                        Lanjut Pembayaran <i class="fas fa-chevron-circle-right"></i></span>
                </a>
            </div>
        </li>
    </ul>
</nav>

<script>
$('#menu').click(function() {
    let kondisi = $('#value_menu').attr('value');

    if (kondisi == 0) {
        $('#container_navbar').addClass('bg-dark');
        $('.container_nav').css("box-shadow", "0px 0px 15px #555");
        $('.container_nav').css("border-radius", "5px");
        
        $('#notif_badge_menu').addClass('hide');
        $('#value_menu').val("1");

        $('.nav-kiri').removeClass('hide');
        $('.nav-kiri').addClass('animated fadeInLeft');

        $('.nav-kanan').removeClass('hide');
        $('.nav-kanan').addClass('animated fadeInRight');

        $('#menu').removeClass('menu-in');
        $('#menu').addClass('menu-out');
    } else {
        $('#container_navbar').removeClass('bg-dark animated fadeIn');
        $('.container_nav').removeAttr("style");

        $('#notif_badge_menu').removeClass('hide');
        $('.navbar-nav').removeClass('bg-dark');
        $('#value_menu').val("0");

        $('.nav-kiri').removeClass('animated fadeInLeft');
        $('.nav-kiri').addClass('hide');

        $('.nav-kanan').removeClass('animated fadeInRight');
        $('.nav-kanan').addClass('hide');

        $('#menu').removeClass('menu-out');
        $('#menu').addClass('menu-in');
    }
});
</script>