<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 	CodeIgniter
 * @subpackage 	SainSuite
 * @author 		Buddy Winangun <buddywinangun[at]gmail[dot]com>
 * @link 		https://bit.ly/buddywinangun
 * @version 	1.0.0
 */

$this->load->model('produk_model');

$setnama = (isset($titel)) 
    ? $set->nama . " &#8211; " . $titel 
    : $set->nama . " &#8211; " . $set->slogan;
$nama = ($this->setting->demo() == true) 
    ? $setnama . " App by @Buddy" 
    : $setnama;
$desc = (isset($desc)) 
    ? $desc 
    : "Aplikasi Toko Online " . $nama;
$keywords = "";
foreach ($this->db->get("@kategori")->result() as $key) {
	$keywords .= "," . $key->nama;
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en-US">

<head>
	<!-- Document Title
	============================================= -->
    <title><?= $nama ?></title>
    <!-- Meta -->
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="racikproject" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="" />
    <meta name="description" content="<?= $desc ?>" />
    <meta name="keywords" content="<?= $keywords ?>">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="<?= $nama ?>">
    <meta itemprop="description" content="<?= $desc ?>">
    <meta itemprop="image" content="<?=base_url($set->favicon); ?>">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@">
    <meta name="twitter:title" content="<?= $nama ?>">
    <meta name="twitter:description" content="<?= $desc ?>">
    <meta name="twitter:creator" content="@">
    <meta name="twitter:image" content="<?=base_url($set->favicon); ?>">
    <!-- Open Graph data -->
    <meta property="fb:app_id" content="<?= $set->fb_pixel ?>">
    <meta property="og:title" content="<?= $nama ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="<?=site_url(); ?>" />
    <meta property="og:image" content="<?=base_url($set->favicon); ?>" />
    <meta property="og:description" content="<?= $desc ?>" />
    <meta property="og:site_name" content="<?= $nama ?>" />
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?=base_url($set->favicon); ?>" />
    <!-- Font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400|Merriweather:400,700&display=swap" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<?php 
    $this->load->add_assets(array(
        'bootstrap.css',
        'font-awesome.min.css',
        'style.css',
        'swiper.css',
        'dark.css',
        'font-icons.css',
        'animate.css',
        'magnific-popup.css',
        'store.css',
        'colors.css',
        'jquery.js',
        'plugins.js',
        'sweetalert2.js',
    ), 'head');
    echo $this->load->get_assets('head');
    ?>
</head>

<body class="stretched">
    
    <!-- The Main Wrapper
    ============================================= -->
	<div id="wrapper" class="clearfix">