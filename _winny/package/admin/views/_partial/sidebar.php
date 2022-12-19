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

<div class="sidebar" style="background-color:#fff">
    <div class="scrollbar-inner sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item <?php echo (isset($menu) AND $menu == 1) ? "active" : "" ?>">
                <a href="<?=site_url("admin")?>">
                    <i class="la la-dashboard"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-title">DATA PESANAN</li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 2) ? "active" : "" ?>">
                <a href="<?=site_url("admin/order")?>">
                    <i class="fas fa-dolly-flatbed"></i>
                    <p>Pesanan</p>
                    <?php
                        $order = $this->sales_model->getJmlPesanan();
                        if($order > 0){
                    ?>
                    <b class="badge badge-danger"><?=$order?></b>
                    <?php } ?>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 13) ? "active" : "" ?>">
                <a href="<?=site_url("admin/preorder")?>">
                    <i class="fas fa-box"></i>
                    <p>Pre Order</p>
                </a>
            </li>
            <!-- <li class="nav-item <?php echo (isset($menu) AND $menu == 4) ? "active" : "" ?>">
                <a href="<?=site_url("admin/notif")?>">
                    <i class="fas fa-comments"></i>
                    <p>Pesan Masuk</p>
                    <?php
                        $order = $this->setting->getJmlPesan();
                        if($order > 0){
                    ?>
                    <b class="badge badge-danger"><?=$order?></b>
                    <?php } ?>
                </a>
            </li> -->
            <li class="nav-title">LAPORAN</li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 14) ? "active" : "" ?>">
                <a href="<?=site_url("admin/laporan/transaksi")?>">
                    <i class="fas fa-chart-area"></i>
                    <p>Laporan Penjualan</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 15) ? "active" : "" ?>">
                <a href="<?=site_url("admin/laporan/user")?>">
                    <i class="fas fa-user-clock"></i>
                    <p>Transaksi User</p>
                </a>
            </li>
            <li class="nav-title">DATA PRODUK</li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 6) ? "active" : "" ?>">
                <a href="<?=site_url("admin/produk")?>">
                    <i class="fas fa-boxes"></i>
                    <p>Daftar Produk</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 7) ? "active" : "" ?>">
                <a href="<?=site_url("admin/kategori")?>">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Kategori Produk</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 8) ? "active" : "" ?>">
                <a href="<?=site_url("admin/variasi")?>">
                    <i class="fas fa-tags"></i>
                    <p>Variasi Produk</p>
                </a>
            </li>
            <li class="nav-title">PROMO & RESELLER</li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 3) ? "active" : "" ?>">
                <a href="<?=site_url("admin/voucher")?>">
                    <i class="fas fa-ticket-alt"></i>
                    <p>Promo / Voucher</p>
                </a>
            </li>
            <!--
            <li class="nav-item <?php echo (isset($menu) AND $menu == 18) ? "active" : "" ?>">
                <a href="<?=site_url("admin/saldo")?>">
                    <i class="fas fa-wallet"></i>
                    <p>Saldo User</p>
                </a>
            </li>
            -->
            <li class="nav-item <?php echo (isset($menu) AND $menu == 9) ? "active" : "" ?>">
                <a href="<?=site_url("admin/agen")?>">
                    <i class="fas fa-store-alt"></i>
                    <p>Agen & Reseller</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 10) ? "active" : "" ?>">
                <a href="<?=site_url("admin/user")?>">
                    <i class="fas fa-users"></i>
                    <p>User Retail</p>
                </a>
            </li>
            <li class="nav-title">PENGATURAN</li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 5) ? "active" : "" ?>">
                <a href="<?=site_url("admin/slider")?>">
                    <i class="fas fa-images"></i>
                    <p>Slider</p>
                </a>
            </li>
            <!-- <li class="nav-item <?php echo (isset($menu) AND $menu == 18) ? "active" : "" ?>">
                <a href="<?=site_url("admin/notif/testimoni")?>">
                    <i class="fas fa-comment-alt"></i>
                    <p>Testimoni</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 16) ? "active" : "" ?>">
                <a href="<?=site_url("admin/notif/booster")?>">
                    <i class="fas fa-bullhorn"></i>
                    <p>Notif Booster</p>
                </a>
            </li>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 17) ? "active" : "" ?>">
                <a href="<?=site_url("admin/blog")?>">
                    <i class="fas fa-comment-dots"></i>
                    <p>Blog Post</p>
                </a>
            </li> -->
            <!-- <li class="nav-item <?php echo (isset($menu) AND $menu == 11) ? "active" : "" ?>">
                <a href="<?=site_url("admin/page")?>">
                    <i class="fas fa-globe-asia"></i>
                    <p>Halaman Statis</p>
                </a>
            </li> -->
            <?php if(isset($_SESSION["gid"]) AND $_SESSION['gid'] == 2){ ?>
            <li class="nav-item <?php echo (isset($menu) AND $menu == 12) ? "active" : "" ?>">
                <a href="<?=site_url("admin/setting")?>">
                    <i class="fas fa-cogs"></i>
                    <p>Pengaturan</p>
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a href="javascript:void(0)" onclick="logout()">
                    <i class="fas fa-power-off"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="main-panel">
    <div class="content">
        <div class="container-fluid">