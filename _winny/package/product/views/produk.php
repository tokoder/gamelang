<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

$kategori = $this->setting->getKategori($data->idcat, "semua");
$kategorinama = is_object($kategori) ? $kategori->nama : "";
$textorder = $data->preorder_id == 0 ? "Beli Sekarang" : "Pre Order";

$level = isset($_SESSION["gid"]) ? $_SESSION["gid"] : 0;
if ($level == 5) {
    $result = $data->hargadistri;
} elseif ($level == 4) {
    $result = $data->hargaagensp;
} elseif ($level == 3) {
    $result = $data->hargaagen;
} elseif ($level == 2) {
    $result = $data->hargareseller;
} else {
    $result = $data->harga;
}

$dbv = $this->produk_model->getVariasiWhere($data->id);

$totalstok = 0;
$hargs = 0;
foreach ($dbv->result() as $rv) {
    $totalstok += $rv->stok;
    if ($level == 5) {
        $harga[] = $rv->hargadistri;
    } elseif ($level == 4) {
        $harga[] = $rv->hargaagensp;
    } elseif ($level == 3) {
        $harga[] = $rv->hargaagen;
    } elseif ($level == 2) {
        $harga[] = $rv->hargareseller;
    } else {
        $harga[] = $rv->harga;
    }
    $hargs += $rv->harga;
}

$warnaid = array();
$sizeid = array();
?>

<!-- Page Title
============================================= -->
<section id="page-title" class="page-title-mini">

    <div class="container clearfix">
        <h1><?php echo $data->nama; ?></h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url(); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url("product?cat=" . $kategori->url); ?>"><?php echo ucwords(strtolower($kategorinama)); ?></a></li>
        </ol>
    </div>

</section><!-- #page-title end -->

<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="single-product">
                <div class="product">
                    <div class="row gutter-40">

                        <div class="col-md-5">

                            <!-- Product Single - Gallery
                            ============================================= -->
                            <div class="product-image">
                                <div class="fslider" data-pagi="false" data-arrows="false" data-thumbs="true">
                                    <div class="flexslider">
                                        <div class="slider-wrap" data-lightbox="gallery">
                                            <?php
                                            $this->db->where("idproduk", $data->id);
                                            $this->db->order_by("jenis", "DESC");
                                            $db = $this->db->get("produk_upload");
                                            $no = 1;
                                            foreach ($db->result() as $res) {
                                                $path = 'uploads/produk/';
                                                $fpath = FCPATH.$path;
                                                $server = site_url($path);
                                                if ( file_exists($fpath.$res->nama) ) {
                                                    $return = $server.$res->nama;
                                                }
                                                else {
                                                    $return = $server."default.png";
                                                }
                                                if ($no == 1) { ?>
                                                    <div class="slide" data-thumb="<?php echo $return; ?>">
                                                        <a href="<?php echo $return; ?>" title="" data-lightbox="gallery-item">
                                                            <img src="<?php echo $return; ?>" alt="">
                                                        </a>
                                                    </div>
                                            <?php
                                                }
                                                $no++;
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="sale-flash badge badge-danger p-2">Sale!</div> -->
                            </div><!-- Product Single - Gallery End -->

                        </div>

                        <div class="col-md-7 product-desc">

                            <div class="d-flex align-items-center justify-content-between">

                                <!-- Product Single - Price
                                ============================================= -->
                                <div class="product-price">
                                    <?php
                                    if ($data->hargacoret > 0) {
                                        echo "<del>IDR " . $this->setting->formUang($data->hargacoret) . "</del>";
                                    }

                                    if ($hargs > 0) {
                                        echo "<ins>IDR " . $this->setting->formUang(min($harga)) . " - " . $this->setting->formUang(max($harga)) . "</ins>";
                                    } else {
                                        echo "<ins>IDR " . $this->setting->formUang($result) . "</ins>";
                                    }
                                    ?>
                                </div><!-- Product Single - Price End -->

                                <!-- Product Single - Rating
                                ============================================= -->
                                <div class="product-rating">
                                    <?php
                                    $ulasan = $this->sales_model->getBintang($data->id);
                                    for ($i = 1; $i <= 5; $i++) {
                                        $color = ($i <= $ulasan["nilai"]) ? "icon-star3" : "icon-star-empty";
                                        echo '<i class="' . $color . '"></i>';
                                    }
                                    ?>
                                </div><!-- Product Single - Rating End -->

                            </div>

                            <div class="line"></div>

                            <!-- Product Single - Meta
                            ============================================= -->
                            <div class="product-meta">
                                <span class="posted_in">Category: <a href="#" rel="tag"><?php echo ucwords(strtolower($kategorinama)); ?></a>.</span>
                                <span class="tagged_as">Ulasan: <a href="#" rel="tag"><?php echo $ulasan["ulasan"]; ?></a></span>
                                <span class="tagged_as">Terjual: <a href="#" rel="tag"><?= $this->sales_model->getTerjualBerapa($data->id); ?></a></span>
                            </div><!-- Product Single - Meta End -->

                            <div class="line"></div>

                            <!-- Product Single - Quantity & Cart Button
                            ============================================= -->
                            <?php if ($this->setting->cekLogin() == true) {
                                if ($dbv->num_rows() == 0) {
                                    $totalstok = $data->stok;
                                }

                                if ($data->preorder_id > 0) {
                                    $t = $this->sales_model->getPreorder($data->id, "idproduk");
                                    $totalorder = 0;
                                    foreach ($t->result() as $r) {
                                        $totalorder += $r->jumlah;
                                    }
                                    $totalstok = $totalstok - $totalorder;
                                }

                                if ($totalstok > 0) { ?>
                                    <?=form_open('', 'class="cart mb-0" id="keranjang"');?>
                                        <input type="hidden" name="idproduk" value="<?php echo $data->id; ?>" />
                                        <input type="hidden" id="variasi" name="variasi" value="0" />
                                        <input type="hidden" id="harga" name="harga" value="<?= $result ?>" />

                                        <?php
                                        if ($dbv->num_rows() > 0) {
                                            foreach ($dbv->result() as $var) {
                                                $dbf = $this->sales_model->getPreorder($var->id, "variasi");
                                                $totalpre = 0;
                                                foreach ($dbf->result() as $rf) {
                                                    $totalpre += $rf->jumlah;
                                                }

                                                $warnaid[] = $var->warna;
                                                $variasi[$var->warna][] = $var->id;
                                                $sizeid[$var->warna][] = $var->size;
                                                $har[$var->warna][] = $var->harga;
                                                $harreseller[$var->warna][] = $var->hargareseller;
                                                $haragen[$var->warna][] = $var->hargaagen;
                                                $haragensp[$var->warna][] = $var->hargaagensp;
                                                $hardistri[$var->warna][] = $var->hargadistri;
                                                if (isset($stoks[$var->warna])) {
                                                    $stoks[$var->warna] += ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
                                                } else {
                                                    $stoks[$var->warna] = ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
                                                }
                                                $stok[$var->warna][] = ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
                                            }
                                            $warnaid = array_unique($warnaid);
                                            $warnaid = array_values($warnaid);
                                        ?>
                                            <div class="col-12 p-lr-0 m-b-6 fs-14">
                                                <?= $data->variasi ?>
                                            </div>
                                            <div class="col-12 p-lr-0 m-b-10 fs-14">
                                                <select class="form-control variasi fs-14" id="warna" required>
                                                    <option value=""> Pilih <?= $data->variasi ?> </option>
                                                    <?php
                                                    for ($i = 0; $i < count($warnaid); $i++) {
                                                        if ($stoks[$warnaid[$i]] > 0) {
                                                            echo "<option value='" . $warnaid[$i] . "'>" . $this->produk_model->getWarna($warnaid[$i], "nama") . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-12 p-lr-0 m-b-6 fs-14">
                                                <?= $data->subvariasi ?>
                                            </div>
                                            <div class="col-12 p-lr-0 m-b-10">
                                                <select class="form-control variasi fs-14" id="size" required>
                                                    <option value="">Pilih <?= $data->subvariasi ?> dulu </option>
                                                </select>
                                            </div>
                                        <?php } ?>

                                        <div class="d-colom d-md-flex justify-content-between align-items-center">
                                            <div class="quantity clearfix">
                                                <input type="button" value="-" class="minus num-product-down">
                                                <input type="number" step="1" min="<?php echo $data->minorder; ?>" class="qty num-product" id="jumlahorder" name="jumlah" value="<?php echo $data->minorder; ?>" required />
                                                <input type="button" value="+" class="plus num-product-up">
                                            </div>

                                            <div>
                                                <button type="submit" id="submit" class="add-to-cart btn button m-0">
                                                    <i class="fa fa-shopping-cart"></i> <?= $textorder ?>
                                                </button>
                                                <!-- <a href="#modal_beli_wa" data-lightbox="inline" data-url="<?= $data->url; ?>" class="btn_beli_wa btn btn-outline-dark">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a> -->
                                                <?php if ($this->sales_model->cekWishlist($data->id) == false) { ?>
                                                    <button type="button" onclick="tambahWishlist(<?= $data->id ?>,'<?= $data->nama ?>')" class="btn btn-md btn-outline-danger btn-wish">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="line"></div>

                                        <div class="m-b-0 p-lr-0 m-t-10">
                                            Catatan pembeli
                                        </div>
                                        <div class="p-lr-0">
                                            <input class="form-control" type="text" name="keterangan" value="">
                                        </div>
                                    </form><!-- Product Single - Quantity & Cart Button End -->
                                <?php
                                } else { ?>
                                    <div class="btn bg-danger text-light">
                                        <?php if ($data->preorder == 0) { ?>
                                            Maaf, Stok telah habis
                                        <?php } else { ?>
                                            Maaf, Kuota Pre Order sudah penuh
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="<?php echo site_url("auth"); ?>" class="btn btn-primary mr-2">
                                    Beli Produk
                                </a>
                                <!-- <a href="#modal_beli_wa" data-lightbox="inline" data-url="<?= $data->url; ?>" class="btn_beli_wa btn btn-outline-dark">
                                    <i class="fab fa-whatsapp"></i> &nbsp;Beli via Whatsapp
                                </a> -->
                            <?php } ?>

                            <!-- Product Single - Short Description
                            ============================================= -->
                            <p><?= $this->security->xss_clean($data->deskripsi) ?></p>
                            <!-- Product Single - Short Description End -->

                            <!-- Product Single - Share
                            ============================================= -->
                            <div class="si-share d-flex justify-content-between align-items-center mt-4">
                                <span>Bagikan:</span>
                                <div>
                                    <a href="https://api.whatsapp.com/send?text=<?= $url ?>" class="social-icon si-borderless si-evernote" target="_blank">
                                        <i class="icon-whatsapp"></i>
                                        <i class="icon-whatsapp"></i>
                                    </a>
                                    <a href="http://www.facebook.com/sharer.php?u=<?= $url ?>" class="social-icon si-borderless si-facebook" target="_blank">
                                        <i class="icon-facebook"></i>
                                        <i class="icon-facebook"></i>
                                    </a>
                                </div>
                            </div><!-- Product Single - Share End -->

                        </div>

                        <div class="w-100"></div>

                        <div class="col-12 mt-5">
                            <div class="tabs clearfix mb-0" id="tab-1">

                                <ul class="tab-nav clearfix">
                                    <li><a href="#tabs-3"><i class="icon-star3"></i><span class="d-none d-md-inline-block"> Ulasan Pembeli</span></a></li>
                                </ul>

                                <div class="tab-container">
                                    <div class="tab-content clearfix" id="tabs-3">

                                        <div id="reviews" class="clearfix">

                                            <ol class="commentlist clearfix">
                                                <?php
                                                if ($review->num_rows() > 0) {
                                                    foreach ($re->result() as $rev) {
                                                        $staron = "<i class='icon-star3'></i>";
                                                        $staroff = "<i class='icon-star-half-full'></i>";
                                                        $star = "";
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            $star .= ($i <= $rev->nilai) ? $staron : $staroff;
                                                        }
                                                        echo '<li class="comment even thread-even depth-1" id="li-comment-1">
                                                            <div id="comment-1" class="comment-wrap clearfix">
                                                                <div class="comment-content clearfix">
                                                                    <div class="comment-author">
                                                                        ' . $this->user_model->getProfil($rev->usrid, "nama", "usrid") . '
                                                                        <span><a href="#" title="">' . $this->setting->ubahTgl("d/m/Y", $rev->tgl) . '</a></span>
                                                                    </div>
                                                                    <p>' . $rev->keterangan . '</p>
                                                                    <div class="review-comment-ratings">' . $star . '</div>
                                                                </div>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </li>';
                                                    }
                                                } else {
                                                    echo "<i>Belum ada ulasan.</i>";
                                                }
                                                ?>

                                            </ol>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="w-100">
                <h4>PRODUK TERKAIT</h4>
                <?php
                $dbs = $this->produk_model->getProdukByCat($kategori->id, $data->id);
                $totalproduk = 0;
                ?>

                <div class="owl-carousel product-carousel carousel-widget" data-margin="30" data-pagi="false" data-autoplay="5000" data-items-xs="1" data-items-md="2" data-items-lg="3" data-items-xl="4">
                    <?php
                    foreach ($dbs->result() as $r) {
                        $level = isset($_SESSION["gid"]) ? $_SESSION["gid"] : 0;
                        if ($level == 5) {
                            $result = $r->hargadistri;
                        } elseif ($level == 4) {
                            $result = $r->hargaagensp;
                        } elseif ($level == 3) {
                            $result = $r->hargaagen;
                        } elseif ($level == 2) {
                            $result = $r->hargareseller;
                        } else {
                            $result = $r->harga;
                        }

                        $dbv = $this->produk_model->getVariasiWhere($data->id);

                        $totalstok = ($dbv->num_rows() > 0) ? 0 : $r->stok;
                        $hargs = 0;
                        $harga = array();
                        foreach ($dbv->result() as $rv) {
                            $totalstok += $rv->stok;
                            if ($level == 5) {
                                $harga[] = $rv->hargadistri;
                            } elseif ($level == 4) {
                                $harga[] = $rv->hargaagensp;
                            } elseif ($level == 3) {
                                $harga[] = $rv->hargaagen;
                            } elseif ($level == 2) {
                                $harga[] = $rv->hargareseller;
                            } else {
                                $harga[] = $rv->harga;
                            }
                            $hargs += $rv->harga;
                        }

                        if ($totalstok > 0 and $totalproduk < 12) {
                            $totalproduk += 1;
                            $wishis = ($this->sales_model->cekWishlist($r->id)) ? "active" : "";
                    ?>
                            <div class="oc-item">
                                <div class="product">
                                    <div class="product-image">
                                        <a href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>">
                                            <img src="<?= $this->produk_model->getFoto($r->id, "utama"); ?>" alt="<?= $r->nama ?>">
                                        </a>
                                        <!-- <div class="badge badge-success p-2">50% Off*</div> -->
                                        <div class="bg-overlay">
                                            <div class="bg-overlay-content align-items-end justify-content-between" data-hover-animate="fadeIn" data-hover-speed="400">
                                                <button class="btn btn-dark mr-2" data-lightbox="ajax" onclick="tambahWishlist(<?= $r->id ?>,'<?= $r->nama ?>')">
                                                    <i class="icon-line-heart <?= $wishis ?>"></i>
                                                </button>
                                                <!-- <a href="include/ajax/shop-item.html" class="btn btn-dark" data-lightbox="ajax"><i class="icon-line-expand"></i></a> -->
                                            </div>
                                            <div class="bg-overlay-bg bg-transparent"></div>
                                        </div>
                                    </div>
                                    <div class="product-desc center">
                                        <div class="product-title">
                                            <h3><a href="<?php echo site_url('product?url=' . strtolower($r->url)); ?>"><?= $r->nama ?></a></h3>
                                        </div>
                                        <div class="product-price">
                                            <?php if ($r->hargacoret > 0) {
                                                echo "<del>IDR " . $this->setting->formUang($r->hargacoret) . '</del>';
                                            }
                                            if ($hargs > 0) {
                                                echo "<ins>IDR " . $this->setting->formUang(min($harga)) . " - " . $this->setting->formUang(max($harga)) . '</ins>';
                                            } else {
                                                echo "<ins>IDR " . $this->setting->formUang($result) . '</ins>';
                                            }
                                            ?>
                                        </div>
                                        <!-- <div class="product-rating">
                                            <i class="icon-star3"></i>
                                            <i class="icon-star3"></i>
                                            <i class="icon-star3"></i>
                                            <i class="icon-star3"></i>
                                            <i class="icon-star-half-full"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>

                <?php
                if ($totalproduk == 0) {
                    echo "<p>Produk Kosong</p>";
                }
                ?>

            </div>
        </div>
    </div>
</section><!-- #content end -->

<div style="display:none;">
    <?php
    for ($i = 0; $i < count($warnaid); $i++) {
        echo "<div id='warna_" . $warnaid[$i] . "'>
				<option value=''> Pilih " . $data->subvariasi . " </option>";
        for ($a = 0; $a < count($sizeid[$warnaid[$i]]); $a++) {
            if ($stok[$warnaid[$i]][$a] > 0) {
                if ($level == 5) {
                    $result = $hardistri[$warnaid[$i]][$a];
                } elseif ($level == 4) {
                    $result = $haragensp[$warnaid[$i]][$a];
                } elseif ($level == 3) {
                    $result = $haragen[$warnaid[$i]][$a];
                } elseif ($level == 2) {
                    $result = $harreseller[$warnaid[$i]][$a];
                } else {
                    $result = $har[$warnaid[$i]][$a];
                }
                echo "<option value='" . $sizeid[$warnaid[$i]][$a] . "' data-stok='" . $stok[$warnaid[$i]][$a] . "' data-harga='" . $result . "' data-variasi='" . $variasi[$warnaid[$i]][$a] . "'>" . $this->setting->getSize($sizeid[$warnaid[$i]][$a], "nama") . "</option>";
            }
        }
        echo "</div>";
    }
    ?>
</div>

<!-- Hidden Input For Checkout WA -->
<input type="hidden" id="nama_produk" value="-">
<input type="hidden" id="varian" value="-">
<input type="hidden" id="subvarian" value="-">
<input type="hidden" id="harga" value="-">
<input type="hidden" id="jml_beli" value="-">
<input type="hidden" id="berat_produk" value="-">
<input type="hidden" id="catatan" value="-">
<input type="hidden" id="ongkir" value="-">
<input type="hidden" id="ekspedisi" value="-">
<input type="hidden" id="nama_pembeli" value="-">
<input type="hidden" id="no_hp" value="-">
<input type="hidden" id="alamat" value="-">
<input type="hidden" id="provinsi" value="-">
<input type="hidden" id="kab" value="-">
<input type="hidden" id="kec" value="-">
<input type="hidden" id="kodepos" value="-">

<script>
	// format uang
	function formUang(data) {
		return data.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
	}

    $('.btn_beli_wa').click(function() {
        let data_id = $(this).attr('data-url');
        let total_harga = parseInt(harga) * parseInt(jml_beli);
        let berat_produk = $('#berat_produk').val();
        let ongkir = $('#ongkir').val();
        let jml_total = jml_beli + parseInt(ongkir);
        let ekspedisi = $('#ekspedisi').val();
        let nama_pembeli = $('#nama_pembeli').val();
        let no_hp = $('#hp').val();
        let alamat = $('#alamat').val();
        let prov = $('#prov').val();
        let kab = $('#kab').val();
        let kec = $('#kec').val();
        let kodepos = $('#kodepos').val();

        $.get("<?= base_url(); ?>order/wasap/cart/" + data_id, function(data) {
            $('#detail_produk_wa').html(data);

            $('.num-product-down2').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                var numProduct = Number($(this).next().val());
                if (numProduct > 1) $(this).next().val(numProduct - 1).trigger("change");
            });

            $('.num-product-up2').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                var numProduct = Number($(this).prev().val());
                $(this).prev().val(numProduct + 1).trigger("change");
            });

            $(".jumlahorder2").change(function() {
                if (parseInt($(this).val()) < parseInt($(this).attr("min"))) {
                    $(this).val($(this).attr("min")).trigger("change");
                }
                if (parseInt($(this).val()) > parseInt($(this).attr("max"))) {
                    $(this).val($(this).attr("max")).trigger("change");
                }
            });

            $(".warna2").on("change", function() {
                let data_variasi = $(this).attr('data_variasi');
                let variasi_selected = $(".warna2 option:selected").attr('data-varian');
                if ($(this).val() != "") {
                    $(".size2").html($(".warna2_" + $(this).val()).html());
                    $('#formnya_varian2').val(variasi_selected);
                } else {
                    $(".size2").html('<option value="">Pilih ' + data_variasi + ' dulu</option>');
                }
                $("#stokrefresh2").html("");
            });

            $(".size2").on("change", function() {
                $(".jumlahorder2").attr("max", $(this).find(":selected").data('stok'));
                $("#stokmaks2").html($(this).find(":selected").data('stok'));
                $("#harga2").val($(this).find(":selected").data('harga'));
                $("#hargacetak2").html("IDR " + formUang($(this).find(":selected").data('harga')));
                $("#stokrefresh2").html("stok: " + $(this).find(":selected").data('stok'));

                let data_subvarian = $('.size option:selected').attr('data-subvarian');
                $('#formnya_subvarian2').val(data_subvarian);
            });

            $("#keranjang2").on("submit", function(e) {
                e.preventDefault();

                var submit = $("#submit2").html();
                $("#submit2").html(
                    "<i class='spinner-border spinner-border-sm text-light'></i> Memproses..."
                );

                let beli_nama_produk = $('#beli_nama_produk2').attr('data-produk');
                let beli_harga = $('#harga2').val();
                let beli_varian = $('#formnya_varian2').val();
                let beli_subvarian = $('#formnya_subvarian2').val();
                let beli_jml_beli = $('#beli_jml_beli2').val();
                let beli_berat_produk = $('#beli_berat_produk2').val();
                let beli_catatan = $('#beli_catatan2').val();

                $('#nama_produk2').val(beli_nama_produk);
                $('#harga2').val(beli_harga);
                $('#varian2').val(beli_varian);
                $('#subvarian2').val(beli_subvarian);
                $('#jml_beli2').val((beli_jml_beli));
                $('#berat_produk2').val(beli_berat_produk);
                $('#catatan2').val(beli_catatan);

                let total_harga = parseInt(beli_harga) * parseInt(beli_jml_beli);

                $.get("<?= base_url(); ?>order/wasap/checkout/" +
                    beli_berat_produk + "/" +
                    beli_nama_produk + "/" +
                    beli_harga + "/" +
                    beli_jml_beli + "/" +
                    total_harga + "/" +
                    beli_varian + "/" +
                    beli_subvarian + "/" +
                    beli_catatan,
                    function(data) {
                        $('#detail_produk_wa').html(data);
                        $("#beli_wa_cart").on("submit", function(e) {
                            e.preventDefault();
                            $("#button_beli_wa").html('Sedang diproses...');
                            var total = parseFloat($("#totalsemua").val());
                            var datar = $(this).serialize();
                            datar = datar + "&" + $("#csrf_name").val() + "=" + $("#csrf_token").val();
                            $.post("<?php echo site_url("order/wasap"); ?>", $("#beli_wa_cart").serialize() + "&" + $("#csrf_name").val() + "=" + $("#csrf_token").val(),
                                function(msg) {
                                    var data = eval("(" + msg + ")");
                                    $(".csrf_token").val(data.token);
                                    if (data.success == true) {
                                        Swal.fire({
                                            title: "Berhasil",
                                            text: "Terima kasih sudah memesan, pesanan anda akan kami proses",
                                            icon: "success"
                                        });
                                        $('#modal_beli_wa').hide();
                                        location.reload(true);
                                    } else {
                                        Swal.fire({
                                            title: "Gagal",
                                            // text: "Gagal membuat pesanan",
                                            text: "Dalam upaya kami meningkatkan kualitas layanan, saat ini sedang melakukan maintenance.",
                                            icon: "error"
                                        });
                                        $('#button_beli_wa').html('<i class="fab fa-whatsapp"></i> Pesan Via WA');
                                    }
                                });
                        });

                        $(".ongkir").change(function() {
                            var ongkir = $(this).val();
                            var harga = $("#totalharga").val();
                            var totalharga = Number(harga) + Number(ongkir);
                        });

                        function hitungOngkir() {
                            let form_nama_penerima = $("#form_nama_penerima").val();
                            var tujuan = $("#tujuan").val();
                            if ((form_nama_penerima != null) && (tujuan > 0)) {
                                var kurir = $(".kurir").val();
                                var service = $(".paket").val();
                                var berat = $("#berat").val();
                                var dari = $("#dari").val();
                                if (kurir != "" && service != "") {
                                    var datar = {
                                        "berat": berat,
                                        "dari": dari,
                                        "tujuan": tujuan,
                                        "kurir": kurir,
                                        "service": service,
                                        [$("#csrf_name").val()]: $("#csrf_token").val()
                                    };
                                    $.post("<?php echo site_url("shipping/check"); ?>", datar, function(msg) {
                                        var data = eval("(" + msg + ")");
                                        $(".csrf_token").val(data.token);
                                        if (data.success == true) {
                                            $("#ongkir").val(data.harga).trigger("change");
                                            if (data.harga == 0 && $(".kurir").val() != "cod") {
                                                errorKurir();
                                            }
                                            calculateOngkir();
                                            $('.button_beli_wa').removeClass('hide');
                                        } else {
                                            $("#ongkir").val(0).trigger("change");
                                            calculateOngkir();
                                            errorKurir();
                                        }
                                    });
                                }
                            } else {
                                Swal.fire("Penting!", "Lengkapi data diri dan alamat terlebih dahulu", "error");
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
                                    if ($(".kurir").val() == "cod") {
                                        sumi == true;
                                    } else {
                                        sumi = false;
                                    }
                                }
                            });
                            var totalsemuashow = sum + parseFloat($("#totalharga").val());

                            if (sumi == false) {
                                $(".pembayaran").hide();
                                $("#error-bayar").show();
                                $('#button_beli_wa').hide();
                                $('#button_beli_wa_false').show();
                            } else {
                                $(".pembayaran").show();
                                $("#error-bayar").hide();
                                $('#button_beli_wa').show();
                                $('#button_beli_wa_false').hide();
                            }

                            $("#total").val(totalsemuashow);
                            $("#totalsemuashow").html(formUang(totalsemuashow));
                            $("#ongkirshow").html(formUang(sum));
                            $(".ongkirshow").html(formUang(sum));
                            $('#totalsemua').val(totalsemuashow);
                            $('#ongkir').val(sum);
                        }

                        function resetOngkir() {
                            $('#totalsemuashow').html('');
                            $('#ongkirshow').html('');
                            $('#totalsemua').val(0);
                            $('#ongkir').val(0);
                            $('#button_beli_wa').hide();
                            $('#button_beli_wa').hide();
                            $('#button_beli_wa_false').show();
                        }

                        function errorKurir() {
                            $("#error-kurir").show();
                        }

                        let form_no_hp = $('#form_no_hp').val();
                        if (form_no_hp = null) {
                            resetOngkir();
                        }

                        //KURIR
                        $(".kurir").change(function() {
                            var me = $(this).val();
                            var datar = {
                                "kurir": $(this).val(),
                                "berat": $("#berat").val(),
                                "tujuan": $("#tujuan").val(),
                                [$("#csrf_name").val()]: $("#csrf_token").val()
                            };
                            $.post("<?php echo site_url("shipping"); ?>", datar, function(msg) {
                                var data = eval("(" + msg + ")");
                                $(".csrf_token").val(data.token);
                                $("#error-kurir").hide();
                                $('#kurir_nama').val(me);
                                $(".paket").html(data.html)
                                $(".ongkir").val("0");
                                $("#ongkirshow").html("0");
                                resetOngkir();
                            });
                        });
                        $(".paket").change(function() {
                            var me = $('.paket option:selected').val();
                            $('#kurir_service').val(me);
                            $("#error-kurir").hide();
                            hitungOngkir();
                        });

                        //LOAD KABUPATEN KOTA & KECAMATAN
                        $("#prov").change(function() {
                            $("#kab").html("<option value=''>Loading...</option>");
                            $("#kec").html("<option value=''>Kecamatan</option>");
                            var me = $("#prov option:selected").attr('data-nama');
                            $('#alamat_prov').val(me);

                            resetOngkir();
                            $.post("<?php echo site_url("regional/kabupaten"); ?>", {
                                    "id": $(this).val(),
                                    [$("#csrf_name").val()]: $("#csrf_token").val()
                                },
                                function(msg) {
                                    var data = eval("(" + msg + ")");
                                    $(".csrf_token").val(data.token);
                                    $("#kab").html(data.html);
                                });
                        });
                        $("#kab").change(function() {
                            resetOngkir();
                            var me = $("#kab option:selected").attr('data-nama');
                            $('#alamat_kab').val(me);
                            $("#kec").html("<option value=''>Loading...</option>");
                            $.post("<?php echo site_url("ongkir/getkec"); ?>", {
                                    "id": $(this).val(),
                                    [$("#csrf_name").val()]: $("#csrf_token").val()
                                },
                                function(msg) {
                                    var data = eval("(" + msg + ")");
                                    $(".csrf_token").val(data.token);
                                    $("#kec").html(data.html);
                                });
                        });
                        $("#kec").change(function() {
                            var data = $(this).find(":selected").val();
                            $("#tujuan").val(data);
                            $(".tujuan").val(data);
                            let tujuan = $("#tujuan").val();
                            var me = $("#kec option:selected").attr('data-nama');
                            $('#alamat_kec').val(me);

                            hitungOngkir();
                        });
                    });
            });
        });
    });
    
    $('.num-product-down').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var numProduct = Number($(this).next().val());
        if (numProduct > 1) $(this).next().val(numProduct - 1).trigger("change");
    });

    $('.num-product-up').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var numProduct = Number($(this).prev().val());
        $(this).prev().val(numProduct + 1).trigger("change");
    });

    $("#jumlahorder").change(function() {
        if (parseInt($(this).val()) < parseInt($(this).attr("min"))) {
            $(this).val($(this).attr("min")).trigger("change");
        }

        if (parseInt($(this).val()) > parseInt($(this).attr("max"))) {
            $(this).val($(this).attr("max")).trigger("change");
        }
    });

    $("#warna").on("change", function() {
        if ($(this).val() != "") {
            $("#size").html($("#warna_" + $(this).val()).html());
        } else {
            $("#size").html("<option value=\"\"> Pilih <?= $data->variasi ?> dulu </option>");
        }
        $("#stokrefresh").html("");
    });

    $("#size").on("change", function() {
        $("#variasi").val($(this).find(":selected").data('variasi'));
        $("#jumlahorder").attr("max", $(this).find(":selected").data('stok'));
        $("#stokmaks").html($(this).find(":selected").data('stok'));
        $("#harga").val($(this).find(":selected").data('harga'));
        $("#hargacetak").html("IDR " + formUang($(this).find(":selected").data('harga')));
        $("#stokrefresh").html("stok: " + $(this).find(":selected").data('stok'));
    });

    $(".btn-wish").click(function() {
        setTimeout(() => {
            $(this).hide("slow");
        }, 1000);
    });

    $("#keranjang").on("submit", function(e) {
        e.preventDefault();
        $("#submit").html("<i class='fas fa-spin fa-spinner'></i> memproses...");

        <?php
        if ($data->preorder_id == 0) { ?>
            $.post("<?php echo site_url("order/cart/add"); ?>", $(this).serialize(), function(msg) {
                var data = eval("(" + msg + ")");
                updateToken(data.token);
                $("#submit").html();
                if (data.success == true) {
                    fbq('track', 'AddToCart', {
                        content_ids: "<?= $data->id ?>",
                        content_type: "<?= $kategorinama ?>",
                        content_name: "<?= $data->nama ?>",
                        currency: "IDR",
                        value: data.total
                    });
                    var nameProduct = $('#js-name-detail').html();
                    Swal.fire(nameProduct, "berhasil ditambahkan ke keranjang", "success").then((value) => {
                        window.location.href = "<?php echo site_url("order/cart"); ?>";
                    });
                } else {
                    Swal.fire("Gagal", "tidak dapat memproses pesanan \n " + data.msg, "error");
                }
            });
        <?php
        } else { ?>
            $.post("<?php echo site_url("order/preorder"); ?>", $(this).serialize(), function(msg) {
                var data = eval("(" + msg + ")");
                $("#submit").html();
                if (data.success == true) {
                    fbq('track', 'AddToCart', {
                        content_ids: "<?= $data->id ?>",
                        content_type: "<?= $kategori->nama ?>",
                        content_name: "<?= $data->nama ?>",
                        currency: "IDR",
                        value: data.total
                    });
                    var nameProduct = $('#js-name-detail').html();
                    Swal.fire(nameProduct, "berhasil mengikuti preorder", "success").then((value) => {
                        window.location.href = "<?php echo site_url("order/preorder/save?predi="); ?>" + data.inv;
                    });
                } else {
                    Swal.fire("Gagal", "tidak dapat memproses pesanan", "error");
                }
            });
        <?php
        } ?>
    });
</script>