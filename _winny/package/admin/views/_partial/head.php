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
<!DOCTYPE html>
<html>
<head>
	<?php $set = $this->setting->globalset("semua"); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?=$set->nama?> Dashboard Management</title>
	<link rel="shortcut icon" type="image/png" href="<?=base_url($this->setting->globalset("favicon"))?>"/>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<?php 
    $head = array(
        'bootstrap.min.css',
        'font-awesome.min.css',
        'bootstrap-datetimepicker-build.css',
        'util.css',
        'ready.css',
        'minmin.css',
        'jquery.js',
    );
    $this->load->add_assets($head, 'head');
    ?>

	<?php if(isset($tiny) AND $tiny == true){ 
    $head = array(
        'tinymce/jquery.tinymce.min.js',
        'tinymce/tinymce.js',
    );
    $this->load->add_assets($head, 'head');
	} ?>
	
	<?php
    echo $this->load->get_assets('head');
	
	$color1 = $set->color1;
	$color2 = $set->color2;
	$colorbody = $set->colorbody;
	$colorbutton = $set->colorbutton;
	$colortext = '#292929';
	$colorwhite = '#ffffff';
	$colorbg = '#fff';
	$coloricon = '#fff';

	if($colorbody == '#0e0e0e'){
		$colortext = '#fff';
		$colorbg = '#000';
	}else{
		$colorbg = '#fff';
	}
	if ($color1 == '#ffffff'){
		$colorwhite = '#111111';
	}
	if ($color2 == '#ffffff'){
		$coloricon = $color1;
	}
	?>
	<script>
    function init_tinymce(selector, min_height) {
        tinymce.init({
            selector: selector,
            min_height: min_height,
			menubar: false,
			statusbar: false,
			base_url: '<?=base_url();?>assets/js/tinymce',
        });
    }
	</script>

	<!-- GENERATED CUSTOM COLOR -->
	<style rel="stylesheet">
		.sidebar .nav .nav-item:hover a, .sidebar .nav .nav-item.active a{
			background: <?=$set->color1?>;
		}
		.badge-color2{
			background: <?=$set->color2?>;
			color: #fff;
		}
		.tabs .tabs-item.active, .tabs .tabs-item:hover{
			border-bottom: 3px solid <?=$set->color1?>;
			color: <?=$set->color1?>;
		}
		.sidebar .nav .nav-title{
			color: <?=$set->color1?>;
		}
		.color1{
			color: <?= $color1;?>
		}
		.color2{
			color: <?= $color2;?>
		}
		.colorwhite{
			color: <?= $colorwhite;?>;
		}
		.colorbg{
			background-color: <?= $colorbg;?>
		}
		.bg-color1{
			background-color: <?= $color1;?>
		}
		.bg-color2{
			background-color: <?= $color2;?>
		}
		.colortext{
			color: <?= $colortext;?>
		}
		.btn-color1{
		    background: <?= $color1;?>;
		    color:  <?= $colorwhite;?>
		}
		.btn-color1:hover{
		    background: <?= $color1;?>;
		    color: <?= $colorwhite;?>;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.btn-outline-color1{
		    background: <?= $colorwhite;?>;
		    color:  <?= $color1;?>;
		}
		.btn-outline-color1:hover{
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		    box-shadow: 0px 0px 3px #555;
		    color:  <?= $color1;?>;
		}
		.btn-colorbutton{
			background-color: <?= $colorbutton;?>;
			color: #fff;
		}
		.btn-colorbutton:hover{
			background: <?= $colorbutton;?>;
		    color: #fff;
		    box-shadow: 0px 0px 3px #555;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.btn-outline-colorbutton{
			background-color: #fff;
			color: <?= $colorbutton;?>;
			border: 1px solid <?= $colorbutton;?>;
		}
		.btn-outline-colorbutton:hover{
			background: <?= $colorbutton;?>;
		    color: #fff;
		    box-shadow: 0px 0px 3px #555;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.color2{
			color: <?= $colorbutton;?>;
		}
		.coloricon{
			color: <?= $coloricon;?>;
		}
		input.border_bottom{
			border: 0px;
			border-radius: 0px;
			border-bottom: 2px solid <?= $color1;?>;
		}
		.box_border_bottom{
			border: 0px;
			border-radius: 0px;
			border-bottom: 3px dashed #ccc;
		}
		.colorbody{
			background-color:  <?= $colorbody;?>;
		}
		.colorfooter{
			background-color: #fff;
		}
		.border-bottom-active{
			border-bottom: 2px solid <?= $color1;?>;
		}
		.border-bottom-nonaktif{
			border-bottom: 0px;
		}.color1{
			color: <?= $color1;?>
		}
		.color2{
			color: <?= $color2;?>
		}
		.colorwhite{
			color: <?= $colorwhite;?>;
		}
		.colorbg{
			background-color: <?= $colorbg;?>
		}
		.bg-color1{
			background-color: <?= $color1;?>
		}
		.bg-color2{
			background-color: <?= $color2;?>
		}
		.colortext{
			color: <?= $colortext;?>
		}
		.btn-color1{
		    background: <?= $color1;?>;
		    color:  <?= $colorwhite;?>
		}
		.btn-color1:hover{
		    background: <?= $color1;?>;
		    color: <?= $colorwhite;?>;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.btn-outline-color1{
		    background: <?= $colorwhite;?>;
		    color:  <?= $color1;?>;
		}
		.btn-outline-color1:hover{
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		    box-shadow: 0px 0px 3px #555;
		    color:  <?= $color1;?>;
		}
		.btn-colorbutton{
			background-color: <?= $colorbutton;?>;
			color: #fff;
		}
		.btn-colorbutton:hover{
			background: <?= $colorbutton;?>;
		    color: #fff;
		    box-shadow: 0px 0px 3px #555;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.btn-outline-colorbutton{
			background-color: #fff;
			color: <?= $colorbutton;?>;
			border: 1px solid <?= $colorbutton;?>;
		}
		.btn-outline-colorbutton:hover{
			background: <?= $colorbutton;?>;
		    color: #fff;
		    box-shadow: 0px 0px 3px #555;
		    webkit-transition: all 0.1s ease-in-out;
		    transition: all 0.1s ease-in-out;
		}
		.color2{
			color: <?= $colorbutton;?>;
		}
		.coloricon{
			color: <?= $coloricon;?>;
		}
		input.border_bottom{
			border: 0px;
			border-radius: 0px;
			border-bottom: 2px solid <?= $color1;?>;
		}
		.box_border_bottom{
			border: 0px;
			border-radius: 0px;
			border-bottom: 3px dashed #ccc;
		}
		.colorbody{
			background-color:  <?= $colorbody;?>;
		}
		.colorfooter{
			background-color: #fff;
		}
		.border-bottom-active{
			border-bottom: 2px solid <?= $color1;?>;
		}
		.border-bottom-nonaktif{
			border-bottom: 0px;
		}
	</style>
</head>
<body>
	<div class="wrapper">