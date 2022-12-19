<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */
?>

<!-- Page Title
============================================= -->
<section id="page-title" class="page-title-mini">
    <div class="container clearfix">
        <h1>Checkout</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item">Checkout</li>
        </ol>
    </div>
</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <?= form_open('', 'class="bg0 p-t-0 p-b-10" id="cekout"'); ?>
            <div class="row gutter-30">
                <div class="col-12 col-lg-6">
                    <div class="row col-mb-30">
                        <!-- Alamat Pengiriman -->
                        <div class="col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="m-0"><i class="fa fa-map-marker-alt"></i> Alamat Pengiriman</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <?php
                                    $this->db->where("usrid", $_SESSION["uid"]);
                                    $this->db->order_by("status", "DESC");
                                    $row = $this->db->get("user_alamat");
                                    ?>
                                    <input type="hidden" id="tujuan" value="" name="tujuan" />

                                    <div class="form-group">
                                        <div class="rs1-select2 rs2-select2 m-b-10 alamatform f-sm-12 f-md-14">
                                            <select class="js-select2 form-control fs-12" name="alamat" id="idalamat">
                                                <option value="">- Pilih Alamat Tujuan -</option>
                                                <option value="0">+ Tambah Alamat Baru</option>
                                                <?php
                                                foreach ($row->result() as $al) {
                                                    $keckab = $this->rajaongkir->getCity($al->idkab)['rajaongkir'];
                                                    if ($al->idkab != null && $keckab['status']['code'] == '200') {
                                                        $kab = $keckab['results']['city_name'];
                                                        echo '<option value="' . $al->id . '" data-tujuan="' . $al->idkab . '">' . strtoupper(strtolower($al->judul . ' - ' . $al->nama)) . ' (' . $kab . ')</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>

                                    <?php
                                    // RAJAONGKIR
                                    foreach ($row->result() as $als) {
                                        $kab = '';
                                        $keckab = $this->rajaongkir->getCity($als->idkab)['rajaongkir'];
                                        if ($als->idkab != null && $keckab['status']['code'] == '200') {
                                            $kab = $keckab['results']['city_name'];
                                            echo "
                                                <div class='alamat mb-3' id='alamat_" . $als->id . "' data-tujuan='" . $al->idkab . "' style='display:none;'>
                                                    <b class='color1'>Nama Penerima:</b><br/>" . strtoupper(strtolower($als->nama)) . "<br/>
                                                    <b class='color1'>No HP:</b><br/>" . $als->nohp . "<br/>
                                                    <b class='color1'>Alamat Lengkap:</b><br/>" . strtoupper(strtolower($als->alamat . "<br/>" . $kab)) . "<br/>KODEPOS " . $als->kodepos . "
                                                </div>
                                                ";
                                        }
                                    }
                                    ?>

                                    <!-- Tambah Alamat Pengiriman -->
                                    <div class="tambahalamat row" style="display:none;">
                                        <div class="col-12 form-group">
                                            <h5 class="d-flex mb-2">Tambah Alamat Pengiriman</h5>
                                        </div>

                                        <div class="col-12 form-group">
                                            <input type="text" name="judul" placeholder="Simpan Sebagai? ex: Alamat Rumah, Alamat Kantor, Dll" class="sm-form-control">
                                        </div>

                                        <div class="col-12 form-group">
                                            <input type="text" name="nama" placeholder="Nama Penerima" class="sm-form-control">
                                        </div>

                                        <div class="col-12 form-group">
                                            <input type="text" name="nohp" placeholder="No Handphone Penerima" class="sm-form-control">
                                        </div>

                                        <div class="col-12 form-group">
                                            <textarea class="sm-form-control" name="alamatbaru" placeholder="Alamat lengkap"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="rs1-select2 rs2-select2 col-12 form-group">
                                                    <select class="js-select2 form-control" name="negara" readonly>
                                                        <option value="ID">Indonesia</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs1-select2 rs2-select2 col-6 form-group">
                                                    <select class="js-select2 form-control" id="prov">
                                                        <option value="">Provinsi</option>
                                                        <?php
                                                        foreach ($prov as $pv) {
                                                            echo '<option value="' . $pv['province_id'] . '">' . $pv['province'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <div class="rs1-select2 rs2-select2 col-6 form-group">
                                                    <select class="js-select2 form-control" id="kab" name="idkab">
                                                        <option value="">Kabupaten</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <!-- <div class="rs1-select2 rs2-select2 col-6 form-group">
                                                        <select class="js-select2 form-control" id="kec" name="idkec">
                                                            <option value="">Kecamatan</option>
                                                        </select>
                                                        <div class="dropDownSelect2"></div>
                                                    </div> -->
                                                <div class="col-6 form-group">
                                                    <input class="form-control f-sm-12 f-md-14" readonly type="number" name="kodepos" id="kodepos" placeholder="Kode POS">
                                                    <input type="hidden" name="idkec">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pesanan Saya -->
                        <div class="col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="m-0"><i class="material-icons f-sm-14 f-md-14">local_mall</i> Pesanan Saya</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table cart m-0">
                                        <thead>
                                            <tr>
                                                <th class="cart-product-thumbnail">&nbsp;</th>
                                                <th class="cart-product-name">Product</th>
                                                <th class="cart-product-quantity">Quantity</th>
                                                <th class="cart-product-subtotal">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalbayar = 0;
                                            $totalberat = 0;
                                            $totaldiskon = 0;
                                            foreach ($data->result() as $res) {
                                                $produk = $this->produk_model->getProduk($res->idproduk, "semua");
                                                $totalbayar += $res->harga * $res->jumlah;
                                                $totalberat += $produk->berat * $res->jumlah;
                                                $totalproduks = $res->harga * $res->jumlah;
                                                $totaldiskon += $res->diskon;
                                                $variasee = $this->produk_model->getVariasi($res->variasi, "semua");
                                                $variasi = ($res->variasi != 0) ? $this->produk_model->getWarna($variasee->warna, "nama") . " " . $produk->subvariasi . " " . $this->produk_model->getSize($variasee->size, "nama") : "";
                                                $variasi = ($res->variasi != 0) ? "<br/><small class='color1'>" . $produk->variasi . ": " . $variasi . "</small>" : "";
                                                ?>
                                                <tr class="cart_item">
                                                    <td class="cart-product-thumbnail">
                                                        <img src="<?php echo $this->produk_model->getFoto($produk->id, "utama"); ?>" alt="Pink Printed Dress" width="64" height="64">
                                                    </td>

                                                    <td class="cart-product-name">
                                                        <?php echo $produk->nama . $variasi; ?>
                                                    </td>

                                                    <td class="cart-product-quantity">
                                                        <div class="quantity clearfix">
                                                            x <?php echo $res->jumlah; ?>
                                                        </div>
                                                    </td>

                                                    <td class="cart-product-subtotal">
                                                        <span class="amount"><?php echo $this->setting->formUang($res->harga); ?></span>
                                                    </td>
                                                </tr>

                                                <input type="hidden" class="idproduks" name="idproduk[]" value="<?php echo $res->id; ?>" />
                                                <input type="hidden" id="totalproduks_<?php echo $res->id; ?>" value="<?php echo $totalproduks; ?>" />
                                                <input type="hidden" id="namaproduks_<?php echo $res->id; ?>" value="<?php echo $produk->nama; ?>" />
                                                <input type="hidden" id="kategoriproduks_<?php echo $res->id; ?>" value="<?php echo $this->setting->getKategori($produk->idcat, "nama"); ?>" />
                                            <?php
                                            }
                                            ?>
                                            <?php if (isset($preorder) && $preorder) :?>
                                                <!-- <input type="hidden" id="totaldp" value="<?php echo $totaldiskon; ?>" /> -->
                                            <?php endif; ?>
                                            <input type="hidden" name="berat" class="berat" id="berat" value="<?php echo $totalberat; ?>" />
                                            <input type="hidden" name="ongkir" class="ongkir" id="ongkir" value="0" />
                                            <input type="hidden" name="dari" id="dari" value="<?php echo $this->setting->getSetting("kota"); ?>" />
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row col-mb-30">
                        <!-- Kurir Pengiriman -->
                        <div class="col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="m-0"><i class="fas fa-shipping-fast"></i> &nbsp; Kurir Pengiriman</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="row m-l-0 m-r-0 p-b-20">
                                        <div class="col-12 form-group">
                                            <div class="rs1-select2 rs2-select2 bor8 bg0 w-full  f-sm-12 f-md-14">
                                                <select class="js-select2 kurir form-control f-sm-12 f-md-14" name="kurir">
                                                    <option value="">Pilih Ekspedisi</option>
                                                    <?php
                                                    // $kur = $this->db->get("@kurir");
                                                    $kur = [];
                                                    // Jika User Biasa
                                                    if ($this->session->userdata('gid') == 1) {
                                                        $kur = array(
                                                            'jne'=>'JNE',
                                                            'sicepat'=>'SiCepat Express',
                                                            'gosend'=>'Gosen/Grab Express'
                                                        );
                                                    } else {
                                                        $kur = array(
                                                            'sentral'=>'Sentral Cargo',
                                                            'lain'=>'Ekspedisi Lain'
                                                        );
                                                    };
                                                    foreach ($kur as $key => $value) {
                                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" name="hargapaket" class="hargapaket" id="hargapaket" value="0" />
                                                <div class="dropDownSelect2"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 form-group" id="paketform" style="display:none;">
                                            <div class="rs1-select2 rs2-select2 bor8 bg0 w-full  f-sm-12 f-md-14">
                                                <select class="js-select2 paket form-control  f-sm-12 f-md-14" name="paket">
                                                    <option value="">Pilih Paket</option>
                                                </select>
                                                <div class="dropDownSelect2"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div id="info-paket"></div>
                                        </div>
                                        <div class="error text-danger col-md-12 m-t-10  f-sm-12 f-md-14" id="error-kurir" style="display:none;">
                                            <span><i class="fa fa-exclamation-circle"></i> pilihan ekspedisi atau paket pengiriman tidak tersedia, silahkan pilih yg lain.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropship -->
                        <!-- 
                        <div class="col-12">
                            <h4 class="d-flex mb-2">Dropship</h4>
                            <div class="dropship">
                                <div class="mb-2">
                                    <button type="button" id="nodrop" class="btn m-0 button-small button m-r-10"><i class="fa fa-check-square"></i> Tidak Dropship</button>
                                    <button type="button" id="yesdrop" class="btn m-0 button-small button"><i class="fa fa-check-square" style="display:none"></i> Dropship</button>
                                </div>
                                <div class="row" id="dropform" style="display:none;">
                                    <div class="col-md-6 form-group">
                                        <input type="text" name="dropship" class="sm-form-control" placeholder="nama/olshop pengirim" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" name="dropshipnomer" class="sm-form-control" placeholder="no telepon" />
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input type="text" name="dropshipalamat" class="sm-form-control" placeholder="alamat pengirim" />
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
                        <!-- Bill -->
                        <div class="col-12 ">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h4 class="m-0"><i class="fas fa-dollar-sign"></i> &nbsp; Cart Totals</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table cart m-0">
                                        <input type="hidden" id="subtotal" value="<?php echo $totalbayar; ?>" />
                                        <input type="hidden" id="total" name="total" value="<?php echo $totalbayar; ?>" />
                                        <tbody>
                                            <tr class="cart_item">
                                                <td class="border-top-0 cart-product-name">
                                                    <strong>Subtotal</strong>
                                                </td>

                                                <td class="border-top-0 cart-product-name">
                                                    Rp <span class="amount" id="subtotalbayar"><?php echo $this->setting->formUang($totalbayar); ?></span>
                                                </td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="cart-product-name">
                                                    <strong>Ongkos Kirim</strong>
                                                </td>

                                                <td class="cart-product-name">
                                                    Rp <span class="amount" id="ongkirshow">0</span>
                                                </td>
                                            </tr>
                                            
                                            <?php if (isset($preorder) && $preorder) :?>
                                                <!-- <tr class="cart_item">
                                                    <td class="cart-product-name">
                                                        <strong>Pembayaran DP</strong>
                                                    </td>

                                                    <td class="cart-product-name">
                                                        - Rp <?= $this->setting->formUang($totaldiskon) ?>
                                                    </td>
                                                </tr> -->
                                            <?php else : ?>
                                                <tr class="cart_item">
                                                    <td class="cart-product-name">
                                                        <strong>Voucher</strong>
                                                    </td>

                                                    <td class="cart-product-name">
                                                        - Rp <span id="diskonshow">0</span>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>

                                            <tr class="cart_item">
                                                <td class="cart-product-name">
                                                    <strong>Total</strong>
                                                </td>

                                                <td class="cart-product-name">
                                                    <span class="amount color lead">
                                                        <strong>
                                                            Rp <span id="totalsemuashow"><?php echo $this->setting->formUang($totalbayar); ?>
                                                        </strong>
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>  
                                <!-- Input Voucher -->
                                <?php if (isset($preorder) && $preorder) :?>
                                    <input type="hidden" class="form-control" id="kodevoucher" name="kodevoucher">
                                    <input type="hidden" name="diskon" id="diskon" value='0' />
                                <?php else : ?>
                                    <div class="card-footer">
                                        <div class="input-group m-t-10 f-sm-12 f-md-14">
                                            <input type="text" class="form-control f-sm-12 f-md-14" placeholder="Masukkan voucher" id="kodevoucher" name="kodevoucher" />
                                            <input type="hidden" name="diskon" id="diskon" value='0' />
                                            <div class="input-group-append">
                                                <button class="btn btn-dark f-sm-12 f-md-14" type="button" onclick="cekVoucher()">Cek Voucher</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif;?>
                            </div>
                            <!-- Cek Voucher -->
                            <?php if (isset($preorder) && $preorder) :?>
                            <?php else : ?>
                                <div class="alert alert-danger vouchergagal mt-2" style="display:none;">
                                    Maaf, Voucher sudah tidak berlaku
                                </div>
                                <div class="alert alert-success vouchersukses mt-2" style="display:none;">
                                    Selamat, Voucher berhasil dipakai dan nikmati potongannya
                                </div>
                                <div class="voucher">
                                    <?php
                                    foreach ($voucher->result() as $v) {
                                        $pot = $this->setting->formUang($v->potongan);
                                        $potongan = $v->tipe == 2 ? "<div class=\"font-bold fs-24 text-success text-center p-tb-12\">Rp " . $pot . "</div>" : '<div class="font-bold fs-38 text-success text-center p-tb-0">' . $pot . "%</div>";
                                        $jenis = $v->jenis == 1 ? "Harga" : "Ongkir";
                                        echo '
                                            <div class="pricing-box voucher-item pricing-extended row align-items-stretch my-2 mx-0" data-kode="' . $v->kode . '">
                                                <div class="pricing-desc col-lg-8 p-2 px-4">
                                                    <div class="pricing-title">
                                                        <h4 class="m-0">' . $v->kode . '</h4>
                                                    </div>
                                                    <div class="pricing-features pt-2 pb-0">
                                                        ' . $v->deskripsi . '<br/>
                                                        <small>minimal pembelian Rp. ' . $this->setting->formUang($v->potonganmin) . '</small>
                                                    </div>
                                                </div>
    
                                                <div class="pricing-action-area col-lg d-flex flex-column justify-content-center p-0">
                                                    <div class="pricing-price">
                                                        <span class="price-unit">' . $potongan . '</span>
                                                    </div>
                                                </div>
                                            </div>';
                                    }
                                    ?>
                                </div> 
                            <?php endif;?>
                        </div>
                        
                        <div class="col-12 ">
                            <div class="card-body bg-danger text-white" id="error-bayar">
                                Belum dapat menyelesaikan pesanan, silahkan lengkapi alamat dan total beserta ongkos kirim terlebih dahulu.
                            </div>
                            <div id="proses" style="display:none;">
                                <div class="alert alert-info">
                                    <i class='fas fa-spin fa-spinner'></i> 
                                    Memproses pesanan, mohon tunggu sebentar...
                                </div>
                            </div>
                            <?php if ($saldo > 0) { ?>
                                <div class="card">
                                    <div class="card-body pembayaran" style="display:none;">
                                        <div class="m-b-12">
                                            <b>Pilih Pembayaran</b>
                                        </div>
                                        <div class="row m-r-0 m-l-0">
                                            <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 col-md-6 p-l-0 p-r-0">
                                                <select class="js-select2" name="metode" id="metode">
                                                    <option value="1">Transfer</option>
                                                    <option value="2">Saldo (Rp
                                                        <?php echo $this->setting->formUang($saldo); ?>)
                                                    </option>
                                                </select>
                                                <div class="dropDownSelect2"></div>
                                            </div>
                                            <div class="col-md-6 row m-r-0 m-l-0">
                                                <?php if ($saldo > 0) { ?>
                                                    <div class="col-md-6">
                                                        Potong Saldo:<br />
                                                        <h5 id="saldo">Rp 0</h5>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-md-6">
                                                    Transfer:<br />
                                                    <h5 id="transfer">Rp <?php echo $this->setting->formUang($totalbayar); ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="metode" value="1" />
                            <?php } ?>
                            <input type="hidden" id="saldoval" value="<?php echo $saldo ?>" />
                            <input type="hidden" name="saldo" id="saldopotong" value="0" />
                            <div class="pembayaran" style="display:none;">
                                <a href="javascript:void(0);" onclick="checkoutNow();" class="btn btn-lg btn-primary btn-block">
                                    Lanjut Pembayaran <i class="fas fa-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</section><!-- #content end -->